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
            'body' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'body.max' => 'Pesan maksimal 1000 karakter',
            'attachment.mimes' => 'Format file yang diizinkan hanya JPG, PNG, atau PDF',
            'attachment.max' => 'Ukuran file maksimal 2MB',
        ]);

        if (!$request->body && !$request->hasFile('attachment')) {
            return response()->json(['errors' => ['body' => ['Pesan atau lampiran tidak boleh kosong']]], 422);
        }

        $attachmentPath = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('chat_attachments', 'public');
            $attachmentType = $file->getClientOriginalExtension() === 'pdf' ? 'document' : 'image';
        }

        $message = $conversation->messages()->create([
            'sender_id' => Auth::id(),
            'body' => $request->body,
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
        ]);

        $conversation->update(['last_message_at' => now()]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'data' => $message]);
        }

        return back();
    }

    public function getMessages(Request $request, Conversation $conversation)
    {
        // Mark messages from user as read
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
