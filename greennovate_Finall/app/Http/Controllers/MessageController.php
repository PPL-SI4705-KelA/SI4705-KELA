<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    /**
     * Update the specified message (Edit pesan).
     */
    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        if ($message->sender_id !== Auth::id()) {
            return response()->json(['message' => 'Anda tidak dapat mengubah pesan ini'], 403);
        }

        $request->validate([
            'body' => 'required|string|max:1000',
        ], [
            'body.required' => 'Teks pesan tidak boleh kosong',
            'body.max' => 'Pesan maksimal 1000 karakter',
        ]);

        $message->update([
            'body' => $request->body,
            'is_edited' => \Illuminate\Support\Facades\DB::raw('true'),
            'edited_at' => $message->is_edited ? $message->edited_at : now(),
        ]);

        return response()->json(['success' => true, 'data' => $message]);
    }

    /**
     * Remove the specified message (Soft Delete).
     */
    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        if ($message->sender_id !== Auth::id()) {
            return response()->json(['message' => 'Anda tidak dapat menghapus pesan ini'], 403);
        }

        // Hapus lampiran fisik jika ada
        if ($message->attachment_path && Storage::disk('public')->exists($message->attachment_path)) {
            Storage::disk('public')->delete($message->attachment_path);
        }

        $message->delete(); // Soft delete

        return response()->json(['success' => true, 'message' => 'Pesan berhasil dihapus']);
    }
}
