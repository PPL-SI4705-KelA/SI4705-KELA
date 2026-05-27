@extends('layouts.auth')

@section('title', 'Hubungi Admin - Greennovate')

@section('content')
<div class="w-full max-w-4xl px-6 mt-8 flex flex-col h-[70vh]">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col h-full overflow-hidden">
        
        <!-- Header -->
        <div class="bg-green-600 p-4 text-white flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold">Hubungi Admin</h2>
                <p class="text-sm text-green-100">Kami siap membantu pertanyaan dan kendala Anda</p>
            </div>
            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
            </div>
        </div>

        <!-- Chat Area -->
        <div id="chat-messages" class="flex-1 p-4 overflow-y-auto bg-gray-50 flex flex-col gap-3">
            @if(!$conversation || $conversation->messages->isEmpty())
                <div class="flex items-center justify-center h-full">
                    <p class="text-gray-500 italic text-center">Belum ada percakapan.<br>Mulai percakapan dengan Admin di sini.</p>
                </div>
            @endif
            <!-- Pesan akan dimuat di sini oleh JS -->
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white border-t border-gray-200">
            <form id="chat-form" class="flex gap-2">
                @csrf
                <input type="text" id="chat-input" name="body" 
                    class="flex-1 border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500" 
                    placeholder="Ketik pesan Anda..." maxlength="1000" autocomplete="off" required>
                <button type="submit" id="chat-submit" class="bg-green-600 hover:bg-green-700 text-white rounded-full px-6 py-2 font-medium transition disabled:opacity-50 flex items-center gap-2">
                    <span>Kirim</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
            <p id="chat-error" class="text-red-500 text-sm mt-2 hidden"></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatSubmit = document.getElementById('chat-submit');
    const chatError = document.getElementById('chat-error');
    let lastMessageId = 0;
    let userId = {{ Auth::id() }};
    const renderedMessageIds = new Set();

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function appendMessage(msg) {
        if (renderedMessageIds.has(msg.id)) return;
        renderedMessageIds.add(msg.id);

        const isMe = msg.sender_id === userId;
        const time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        const wrapper = document.createElement('div');
        wrapper.className = `flex ${isMe ? 'justify-end' : 'justify-start'}`;
        
        const bubble = document.createElement('div');
        bubble.className = `max-w-[75%] rounded-2xl px-4 py-2 ${isMe ? 'bg-green-600 text-white rounded-tr-none' : 'bg-gray-200 text-gray-800 rounded-tl-none'}`;
        
        const body = document.createElement('p');
        body.className = 'text-sm break-words';
        body.textContent = msg.body;
        
        const meta = document.createElement('span');
        meta.className = `text-[10px] block mt-1 text-right ${isMe ? 'text-green-200' : 'text-gray-500'}`;
        meta.textContent = time;

        bubble.appendChild(body);
        bubble.appendChild(meta);
        wrapper.appendChild(bubble);
        
        // Hapus teks "Belum ada percakapan" jika ada
        const emptyState = chatMessages.querySelector('.italic');
        if (emptyState) emptyState.remove();

        chatMessages.appendChild(wrapper);
        scrollToBottom();
        
        if (msg.id > lastMessageId) {
            lastMessageId = msg.id;
        }
    }

    function fetchMessages() {
        fetch(`{{ route('chat.messages') }}?last_id=${lastMessageId}`)
            .then(res => res.json())
            .then(data => {
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(msg => appendMessage(msg));
                }
            })
            .catch(err => console.error(err));
    }

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const body = chatInput.value.trim();
        
        if (!body) {
            chatError.textContent = 'Pesan tidak boleh kosong';
            chatError.classList.remove('hidden');
            return;
        }
        if (body.length > 1000) {
            chatError.textContent = 'Pesan maksimal 1000 karakter';
            chatError.classList.remove('hidden');
            return;
        }

        chatError.classList.add('hidden');
        chatSubmit.disabled = true;
        
        const formData = new FormData();
        formData.append('body', body);
        formData.append('_token', document.querySelector('input[name="_token"]').value);

        fetch(`{{ route('chat.send') }}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                chatInput.value = '';
                fetchMessages(); // Ambil ulang pesan termasuk yang baru dikirim
            } else if (data.errors) {
                chatError.textContent = data.errors.body[0] || 'Gagal mengirim pesan.';
                chatError.classList.remove('hidden');
            }
        })
        .catch(err => {
            chatError.textContent = 'Gagal mengirim pesan. Silakan coba lagi.';
            chatError.classList.remove('hidden');
        })
        .finally(() => {
            chatSubmit.disabled = false;
            chatInput.focus();
        });
    });

    // Initial fetch and polling
    fetchMessages();
    setInterval(fetchMessages, 3000);
});
</script>
@endpush
