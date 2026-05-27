<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminChatController extends Controller
{
    public function index()
    {
        $conversations = Conversation::with('user')
            ->withCount(['messages as unread_count' => function ($query) {
                // Count messages where sender is not admin and is_read is false
                $query->whereRaw('is_read = false')
                      ->whereHas('sender', function($q) {
                          $q->where('role', 'user');
                      });
            }])
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('admin.chat.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $conversation->load('user');

        // Mark all user messages in this conversation as read
        $conversation->messages()
            ->whereRaw('is_read = false')
            ->whereHas('sender', function($q) {
                $q->where('role', 'user');
            })
            ->update([
                'is_read' => \Illuminate\Support\Facades\DB::raw('true'),
                'read_at' => now(),
            ]);

        return view('admin.chat.show', compact('conversation'));
    }

    public function reply(Request $request, Conversation $conversation)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ], [
            'body.required' => 'Pesan tidak boleh kosong',
            'body.max' => 'Pesan maksimal 1000 karakter',
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => Auth::id(),
            'body' => $request->body,
        ]);

        $conversation->update(['last_message_at' => now()]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'data' => $message]);
        }

        return back();
    }

    public function getMessages(Request $request, Conversation $conversation)
    {
        $query = $conversation->messages()->with('sender')->orderBy('created_at', 'asc');

        if ($request->has('last_id')) {
            $query->where('id', '>', $request->last_id);
        }

        $messages = $query->get();

        return response()->json($messages);
    }
}
