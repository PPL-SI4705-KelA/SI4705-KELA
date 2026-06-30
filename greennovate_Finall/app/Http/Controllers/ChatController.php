<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Security check, although middleware handles this, good to be safe if someone with other role bypasses
        if ($user->role !== 'user') {
            return redirect()->route($user->role . '.dashboard');
        }

        $conversation = Conversation::where('user_id', $user->id)->first();
        return view('chat.index', compact('conversation'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ], [
            'body.required' => 'Pesan tidak boleh kosong',
            'body.max' => 'Pesan maksimal 1000 karakter',
        ]);

        $conversation = Conversation::firstOrCreate(
            ['user_id' => Auth::id()],
            ['status' => 'open']
        );

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

    public function getMessages(Request $request)
    {
        if ($request->has('conversation_id')) {
            $requestedConv = Conversation::find($request->conversation_id);
            if ($requestedConv && $requestedConv->user_id !== Auth::id()) {
                return response()->json(['message' => 'Anda tidak memiliki akses ke percakapan ini'], 403);
            }
        }

        $conversation = Conversation::where('user_id', Auth::id())->first();

        if (!$conversation) {
            return response()->json([]);
        }

        // Mark messages from admin as read
        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->whereRaw('is_read = false')
            ->update(['is_read' => \Illuminate\Support\Facades\DB::raw('true'), 'read_at' => now()]);

        $query = $conversation->messages()->with('sender')->orderBy('created_at', 'asc');

        if ($request->has('last_id')) {
            $query->where('id', '>', $request->last_id);
        }

        $messages = $query->get();

        return response()->json($messages);
    }
}
