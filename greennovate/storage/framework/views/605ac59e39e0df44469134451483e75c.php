<?php $__env->startSection('title', 'Detail Percakapan – Admin Greennovate'); ?>
<?php $__env->startSection('page-title', 'Detail Percakapan'); ?>
<?php $__env->startSection('page-subtitle', 'Percakapan dengan ' . $conversation->user->name); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <a href="<?php echo e(route('admin.chat.index')); ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900 transition font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col h-[65vh]">
    <!-- Header -->
    <div class="bg-gray-50 border-b border-gray-200 p-4 flex items-center justify-between rounded-t-xl">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold">
                <?php echo e(strtoupper(substr($conversation->user->name, 0, 1))); ?>

            </div>
            <div>
                <h3 class="font-bold text-gray-900"><?php echo e($conversation->user->name); ?></h3>
                <p class="text-xs text-gray-500"><?php echo e($conversation->user->email); ?></p>
            </div>
        </div>
        <div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($conversation->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                Status: <?php echo e(ucfirst($conversation->status)); ?>

            </span>
        </div>
    </div>

    <!-- Chat Area -->
    <div id="chat-messages" class="flex-1 p-6 overflow-y-auto bg-white flex flex-col gap-4">
        <!-- Messages will be loaded via JS -->
    </div>

    <!-- Input Area -->
    <div class="p-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
        <form id="chat-form" method="POST" action="<?php echo e(route('admin.chat.reply', $conversation->id)); ?>" class="flex gap-2 items-center" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <!-- File Input -->
            <label for="chat-attachment" class="cursor-pointer text-gray-500 hover:text-green-600 transition p-2 bg-gray-200 rounded-full shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
            </label>
            <input type="file" id="chat-attachment" name="attachment" class="hidden" accept=".jpg,.jpeg,.png,.pdf">

            <input type="text" id="chat-input" name="body" 
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 text-sm shadow-sm" 
                placeholder="Tulis balasan..." maxlength="1000" autocomplete="off" required>
            <button type="submit" id="chat-submit" class="bg-green-600 hover:bg-green-700 text-white rounded-lg px-6 py-2.5 font-medium transition shadow-sm disabled:opacity-50 flex items-center gap-2">
                <span>Balas</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </button>
        </form>
        <!-- Attachment Preview Name -->
        <p id="attachment-name" class="text-xs text-green-600 font-medium mt-1 hidden"></p>
        <p id="chat-error" class="text-red-500 text-sm mt-1 hidden"></p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatSubmit = document.getElementById('chat-submit');
    const chatError = document.getElementById('chat-error');
    const chatAttachment = document.getElementById('chat-attachment');
    const attachmentName = document.getElementById('attachment-name');
    
    chatAttachment.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            attachmentName.textContent = 'File: ' + this.files[0].name;
            attachmentName.classList.remove('hidden');
        } else {
            attachmentName.classList.add('hidden');
        }
    });

    let lastMessageId = 0;
    let adminId = <?php echo e(Auth::id()); ?>;
    const conversationId = <?php echo e($conversation->id); ?>;
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
        senderLabel.textContent = isAdmin ? 'Anda (Admin)' : '<?php echo e($conversation->user->name); ?>';
        bubbleContainer.appendChild(senderLabel);

        const bubble = document.createElement('div');
        bubble.className = `rounded-2xl px-4 py-2 text-sm break-words shadow-sm ${isAdmin ? 'bg-green-600 text-white rounded-tr-none' : 'bg-gray-100 text-gray-800 rounded-tl-none border border-gray-200'}`;
        
        if (msg.attachment_path) {
            const attachmentUrl = `/storage/${msg.attachment_path}`;
            if (msg.attachment_type === 'image') {
                const img = document.createElement('img');
                img.src = attachmentUrl;
                img.className = 'max-w-full h-auto rounded-lg mb-2 cursor-pointer bg-white';
                img.style.maxHeight = '200px';
                img.onclick = () => window.open(attachmentUrl, '_blank');
                bubble.appendChild(img);
            } else {
                const link = document.createElement('a');
                link.href = attachmentUrl;
                link.target = '_blank';
                link.className = `flex items-center gap-2 px-3 py-2 rounded-lg mb-2 text-sm font-medium ${isAdmin ? 'bg-green-700 text-white hover:bg-green-800' : 'bg-gray-300 text-gray-800 hover:bg-gray-400'}`;
                link.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Buka Dokumen`;
                bubble.appendChild(link);
            }
        }

        if (msg.body) {
            const body = document.createElement('p');
            body.className = 'text-sm break-words';
            body.textContent = msg.body;
            bubble.appendChild(body);
        }
        
        const meta = document.createElement('div');
        meta.className = 'flex items-center gap-1 text-[10px] text-gray-400 mt-0.5 px-1';
        meta.innerHTML = `<span>${time}</span>`;
        
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
        fetch(`<?php echo e(route('admin.chat.messages', $conversation->id)); ?>?last_id=${lastMessageId}`)
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
        const hasFile = chatAttachment.files.length > 0;
        
        if (!body && !hasFile) {
            chatError.textContent = 'Pesan atau lampiran tidak boleh kosong';
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
        if (hasFile) formData.append('attachment', chatAttachment.files[0]);
        formData.append('_token', document.querySelector('input[name="_token"]').value);

        fetch(`<?php echo e(route('admin.chat.reply', $conversation->id)); ?>`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                chatInput.value = '';
                chatAttachment.value = '';
                attachmentName.classList.add('hidden');
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\egiag\Downloads\Gabungan\greennovate_final\resources\views/admin/chat/show.blade.php ENDPATH**/ ?>