<x-app-layout>
    <div class="flex flex-col h-[calc(100vh-64px)] bg-[#F8FAFC]">
        <!-- Header Chat -->
        <div class="bg-slate-900 border border-white/5/80 border-b border-white/5 px-6 py-4 flex items-center justify-between shrink-0 sticky top-0 z-50">
            <div class="flex items-center">
                <a href="{{ route('chat.index') }}" class="mr-4 text-slate-400 hover:text-indigo-600 transition-all p-2 hover:bg-indigo-50 rounded-full group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div class="flex items-center">
                    <div class="relative">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-indigo-500/30">
                            {{ substr($receiver->name, 0, 1) }}
                        </div>
                        @if($receiver->isOnline())
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full shadow-sm animate-pulse" title="Online"></span>
                        @else
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-slate-300 border-2 border-white rounded-full shadow-sm" title="Offline"></span>
                        @endif
                    </div>
                    <div class="ml-3">
                        <h1 class="text-base font-bold text-slate-200 leading-tight">
                            {{ $receiver->name }}
                        </h1>
                        <p class="text-[11px] text-indigo-500 font-semibold tracking-wide uppercase mt-0.5">
                            {{ $receiver->role_label }}
                            @if($receiver->satker)
                                <span class="text-slate-400 mx-1">•</span> {{ $receiver->satker->nama_satker }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-1">
                <button title="Info" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-slate-800/50 rounded-full transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="flex-1 overflow-hidden relative">
            <!-- Subtle Background Pattern -->
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
            
            <div id="chat-messages" class="relative h-full overflow-y-auto px-4 py-6 sm:px-6 space-y-6 pb-24 scroll-smooth">
                <!-- Messages will be injected here -->
                <div class="flex justify-center py-10">
                    <div class="flex items-center space-x-2 text-indigo-500">
                        <div class="w-2 h-2 bg-current rounded-full animate-bounce" style="animation-delay: 0s"></div>
                        <div class="w-2 h-2 bg-current rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-current rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="bg-slate-900 border border-white/5 p-4 shrink-0 z-20">
            <div class="max-w-4xl mx-auto">
                <form id="chat-form" class="relative flex items-end gap-3 bg-slate-800/50 p-2 rounded-3xl border border-white/10 shadow-sm focus-within:ring-2 focus-within:ring-indigo-100 focus-within:border-indigo-300 transition-all duration-300">
                    <div class="flex-1">
                        <textarea 
                            id="message-input" 
                            name="message" 
                            class="w-full pl-4 pr-4 py-3 bg-transparent border-0 focus:ring-0 focus:outline-none text-slate-300 placeholder:text-slate-400 resize-none max-h-32 text-[15px] leading-relaxed" 
                            rows="1"
                            placeholder="Ketik pesan Anda..." 
                            required
                            style="min-height: 44px;"
                        ></textarea>
                    </div>
                    <button 
                        type="submit" 
                        id="send-btn"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-3 font-medium transition-all shadow-md shadow-indigo-500/20 hover:shadow-lg hover:shadow-indigo-500/30 hover:scale-105 active:scale-95 flex-shrink-0 mb-[2px] mr-[2px]"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform rotate-90 ml-0.5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                        </svg>
                    </button>
                </form>
                <div class="mt-2 text-center">
                    <p class="text-[10px] text-slate-400 tracking-wide font-medium">Enter untuk kirim • Shift + Enter baris baru</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const receiverId = {{ $receiver->id }};
        const currentUserId = {{ auth()->id() }};
        const chatContainer = document.getElementById('chat-messages');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const sendBtn = document.getElementById('send-btn');
        
        // Auto-resize textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
            if(this.value === '') this.style.height = '44px';
        });

        // Handle Enter key
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });
        
        let lastMessageCount = 0;
        let isFirstLoad = true;

        function scrollToBottom(smooth = false) {
            chatContainer.scrollTo({
                top: chatContainer.scrollHeight,
                behavior: smooth ? 'smooth' : 'auto'
            });
        }

        function formatTime(dateString) {
            const options = { hour: '2-digit', minute: '2-digit' };
            return new Date(dateString).toLocaleTimeString([], options);
        }

        function fetchMessages() {
            fetch(`{{ route('chat.messages', $receiver->id) }}`)
                .then(response => response.json())
                .then(messages => {
                    if (messages.length !== lastMessageCount || isFirstLoad) {
                        try {
                            const isAtBottom = chatContainer.scrollHeight - chatContainer.scrollTop <= chatContainer.clientHeight + 100;
                            renderMessages(messages);
                            lastMessageCount = messages.length;
                            
                            if (isFirstLoad) {
                                scrollToBottom(false);
                                isFirstLoad = false;
                            } else if (isAtBottom) {
                                scrollToBottom(true);
                            }
                        } catch (e) {
                            console.error("Render error", e);
                        }
                    }
                })
                .catch(error => console.error('Error fetching messages:', error));
        }

        function renderMessages(messages) {
            if (messages.length === 0) {
                chatContainer.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-slate-400">
                        <div class="w-20 h-20 bg-slate-900 border border-white/5 rounded-3xl flex items-center justify-center shadow-sm mb-6 relative overflow-hidden group">
                           <div class="absolute inset-0 bg-indigo-50 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-200 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <p class="font-semibold text-slate-400 mb-1">Mulai obrolan baru</p>
                        <p class="text-xs text-slate-400 max-w-xs text-center">Kirim pesan pertama Anda kepada {{ $receiver->name }} sekarang.</p>
                    </div>
                `;
                return;
            }

            let html = '';
            let lastDate = null;
            let lastSenderId = null;

            messages.forEach((msg, index) => {
                const isMe = msg.sender_id === currentUserId;
                const msgDate = new Date(msg.created_at).toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                const isSameSender = lastSenderId === msg.sender_id;
                
                // Date separator
                if (msgDate !== lastDate) {
                    html += `
                        <div class="flex justify-center my-8">
                            <span class="px-4 py-1.5 bg-slate-200/60 rounded-full text-[11px] text-slate-400 font-semibold tracking-wide uppercase shadow-sm">${msgDate}</span>
                        </div>
                    `;
                    lastDate = msgDate;
                }

                // Bubble styling
                // Saya (Kanan): Warna Indigo gradient, border radius khusus
                // Teman (Kiri): Warna Putih, border halus, border radius khusus
                
                const bubbleClass = isMe 
                    ? 'bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-[20px] rounded-br-[4px] shadow-sm shadow-indigo-500/10' 
                    : 'bg-slate-900 border border-white/5 text-slate-300 border border-white/5 rounded-[20px] rounded-bl-[4px] shadow-sm';
                
                const alignClass = isMe ? 'justify-end' : 'justify-start';
                const containerClass = isMe ? 'items-end' : 'items-start';
                const mtClass = isSameSender ? 'mt-1.5' : 'mt-5';

                html += `
                    <div class="flex ${alignClass} ${mtClass} group px-1 animate-fadeIn">
                        <div class="flex flex-col ${containerClass} max-w-[85%] md:max-w-[70%] lg:max-w-[60%]">
                            <div class="flex items-center gap-2 w-full ${isMe ? 'flex-row' : 'flex-row-reverse'}">
                                ${isMe ? `
                                    <button onclick="deleteMessage(${msg.id})" class="opacity-0 group-hover:opacity-100 p-1.5 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-full transition-all focus:outline-none flex-shrink-0" title="Hapus pesan (karena salah ketik)">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                ` : ''}
                                <div class="${bubbleClass} px-5 py-3 relative text-[15px] leading-relaxed break-words transition-all hover:shadow-md flex-1">
                                    ${msg.message}
                                </div>
                            </div>
                            <div class="flex items-center mt-1 space-x-1.5 px-1 select-none">
                                <span class="text-[10px] text-slate-400 font-medium tracking-wide">
                                    ${formatTime(msg.created_at)}
                                </span>
                                ${isMe ? (msg.is_read 
                                    ? '<svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6L7 17l-5-5m20-2l-7.5 7.5L13 16" /></svg>' 
                                    : '<svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>') : ''}
                            </div>
                        </div>
                    </div>
                `;
                
                lastSenderId = msg.sender_id;
            });

            if (chatContainer.innerHTML !== html) { 
                 chatContainer.innerHTML = html;
            }
        }

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;

            // Optimistic UI update
            messageInput.disabled = true;
            sendBtn.disabled = true;
            sendBtn.classList.add('opacity-75', 'cursor-not-allowed');

            fetch(`{{ route('chat.store', $receiver->id) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(() => {
                messageInput.value = '';
                messageInput.style.height = '44px'; // Reset height
                messageInput.disabled = false;
                sendBtn.disabled = false;
                sendBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                messageInput.focus();
                fetchMessages(); // Force refresh
                setTimeout(() => scrollToBottom(true), 100);
            })
            .catch(err => {
                console.error(err);
                messageInput.disabled = false;
                sendBtn.disabled = false;
                sendBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                window.showAlert('Gagal', 'Gagal mengirim pesan.', 'error');
            });
        });

        window.deleteMessage = function(chatId) {
            window.confirmDialog({
                title: 'Hapus Pesan?',
                message: 'Pesan akan dihapus permanen. Gunakan jika ada salah ketik.',
                type: 'warning',
                confirmText: 'Ya, Hapus'
            }, () => {
                fetch(`/chat/${chatId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        fetchMessages();
                    } else {
                        window.showAlert('Gagal', data.error || 'Tidak dapat menghapus pesan', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    window.showAlert('Gagal', 'Terjadi kesalahan sistem saat menghapus.', 'error');
                });
            });
        };

        // Initial load
        fetchMessages();

        // Polling every 2 seconds
        setInterval(fetchMessages, 2000);

        // Auto focus
        messageInput.focus();
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out forwards;
        }
        
        /* Custom scrollbar for chat */
        #chat-messages::-webkit-scrollbar {
            width: 5px;
        }
        #chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }
        #chat-messages::-webkit-scrollbar-thumb {
            background: rgba(99, 102, 241, 0.2); 
            border-radius: 20px;
        }
        #chat-messages::-webkit-scrollbar-thumb:hover {
            background: rgba(99, 102, 241, 0.4); 
        }
    </style>
    @endpush
</x-app-layout>
