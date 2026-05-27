@extends('layouts.admin')

@section('title', 'Detail Percakapan – Admin Greennovate')
@section('page-title', 'Detail Percakapan')
@section('page-subtitle', 'Percakapan dengan ' . $conversation->user->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.chat.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900 transition font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col h-[65vh]">
    <!-- Header -->
    <div class="bg-gray-50 border-b border-gray-200 p-4 flex items-center justify-between rounded-t-xl">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold">
                {{ strtoupper(substr($conversation->user->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="font-bold text-gray-900">{{ $conversation->user->name }}</h3>
                <p class="text-xs text-gray-500">{{ $conversation->user->email }}</p>
            </div>
        </div>
        <div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $conversation->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                Status: {{ ucfirst($conversation->status) }}
            </span>
        </div>
    </div>

    <!-- Chat Area -->
    <div id="chat-messages" class="flex-1 p-6 overflow-y-auto bg-white flex flex-col gap-4">
        <!-- Messages will be loaded via JS -->
    </div>

    <!-- Input Area -->
    <div class="p-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
        <form id="chat-form" class="flex gap-2">
            @csrf
            <input type="text" id="chat-input" name="body" 
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 text-sm shadow-sm" 
                placeholder="Tulis balasan..." maxlength="1000" autocomplete="off" required>
            <button type="submit" id="chat-submit" class="bg-green-600 hover:bg-green-700 text-white rounded-lg px-6 py-2.5 font-medium transition shadow-sm disabled:opacity-50 flex items-center gap-2">
                <span>Balas</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </button>
        </form>
        <p id="chat-error" class="text-red-500 text-sm mt-2 hidden"></p>
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
    let adminId = {{ Auth::id() }};
    const conversationId = {{ $conversation->id }};
    const renderedMessageIds = new Set();

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function appendMessage(msg) {
        if (renderedMessageIds.has(msg.id)) return;
        renderedMessageIds.add(msg.id);

        const isAdmin = msg.sender_id === adminId;
        const time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        const wrapper = document.createElement('div');
        wrapper.className = `flex ${isAdmin ? 'justify-end' : 'justify-start'}`;
        
        const bubbleContainer = document.createElement('div');
        bubbleContainer.className = `max-w-[75%] flex flex-col gap-1 ${isAdmin ? 'items-end' : 'items-start'}`;

        const senderLabel = document.createElement('span');
        senderLabel.className = 'text-[11px] font-medium text-gray-500 px-1';
        senderLabel.textContent = isAdmin ? 'Anda (Admin)' : '{{ $conversation->user->name }}';
        bubbleContainer.appendChild(senderLabel);

        const bubble = document.createElement('div');
        bubble.className = `rounded-2xl px-4 py-2 text-sm break-words shadow-sm ${isAdmin ? 'bg-green-600 text-white rounded-tr-none' : 'bg-gray-100 text-gray-800 rounded-tl-none border border-gray-200'}`;
        bubble.textContent = msg.body;
        
        const meta = document.createElement('div');
        meta.className = 'flex items-center gap-1 text-[10px] text-gray-400 mt-0.5 px-1';
        meta.textContent = time;

        bubbleContainer.appendChild(bubble);
        bubbleContainer.appendChild(meta);
        wrapper.appendChild(bubbleContainer);

        chatMessages.appendChild(wrapper);
        scrollToBottom();
        
        if (msg.id > lastMessageId) {
            lastMessageId = msg.id;
        }
    }

    function fetchMessages() {
        fetch(`{{ route('admin.chat.messages', $conversation->id) }}?last_id=${lastMessageId}`)
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

        fetch(`{{ route('admin.chat.reply', $conversation->id) }}`, {
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
                fetchMessages();
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
