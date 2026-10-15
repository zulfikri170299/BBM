<x-app-layout>
    <div class="flex flex-col h-[calc(100vh-64px)] bg-[#0b141a] text-[#e9edef] font-sans antialiased overflow-hidden select-none">
        
        <!-- WhatsApp Web Header -->
        <div class="bg-[#202c33] border-b border-[#222d34] px-4 py-2.5 shrink-0 sticky top-0 z-40 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <!-- Back Button -->
                <a href="{{ route('chat.index') }}" class="text-[#aebac1] hover:text-[#e9edef] p-1.5 rounded-full hover:bg-[#374248] transition-colors" title="Kembali">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>

                <!-- Contact Avatar & Info -->
                <div class="flex items-center gap-3 cursor-pointer" onclick="document.getElementById('wa-contact-modal').classList.remove('hidden')">
                    <div class="relative shrink-0">
                        @if($receiver->has_profile_photo)
                            <img src="{{ asset('storage/' . $receiver->profile_photo_path) }}" alt="{{ $receiver->name }}" class="w-10 h-10 rounded-full object-cover" onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                            <div class="hidden w-10 h-10 rounded-full bg-[#6a1b9a] flex items-center justify-center text-white font-bold text-base">
                                {{ strtoupper(substr($receiver->name, 0, 1)) }}
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-full bg-[#5c255c] flex items-center justify-center text-white font-semibold text-lg">
                                {{ strtoupper(substr($receiver->name, 0, 1)) }}
                            </div>
                        @endif

                        @if($receiver->isOnline())
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-[#00a884] border-2 border-[#202c33] rounded-full" title="Online"></span>
                        @endif
                    </div>

                    <div class="min-w-0">
                        <h1 class="text-[16px] font-medium text-[#e9edef] leading-tight truncate">
                            {{ $receiver->name }}
                        </h1>
                        <p class="text-[12px] text-[#8696a0] leading-none mt-1 truncate">
                            @if($receiver->isOnline())
                                <span class="text-[#00a884]">online</span>
                            @else
                                {{ $receiver->role_label }} @if($receiver->satker) • {{ $receiver->satker->nama_satker }} @endif
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- WhatsApp Header Action Icons -->
            <div class="flex items-center space-x-1 sm:space-x-3 text-[#aebac1]">
                <!-- Search Icon -->
                <button type="button" class="p-2 hover:bg-[#374248] rounded-full transition-colors" title="Cari di percakapan">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- Menu Icon (3 dots) -->
                <button type="button" onclick="document.getElementById('wa-contact-modal').classList.remove('hidden')" class="p-2 hover:bg-[#374248] rounded-full transition-colors" title="Info Kontak">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Chat Area with WhatsApp Doodle Background -->
        <div class="flex-1 overflow-hidden relative bg-[#0b141a]">
            <!-- WhatsApp Doodle Texture -->
            <div class="absolute inset-0 pointer-events-none opacity-40" style="background-image: url('{{ asset('images/whatsapp-bg.svg') }}'); background-repeat: repeat; background-size: 380px 380px;"></div>

            <!-- Messages Scroll Container -->
            <div id="chat-messages" class="h-full overflow-y-auto px-4 sm:px-12 md:px-20 py-4 pb-20 scroll-smooth relative z-10">
                <div class="max-w-4xl mx-auto space-y-2" id="chat-stream-wrapper">
                    <!-- Loading Placeholder -->
                    <div id="chat-loading-placeholder" class="flex flex-col items-center justify-center py-16 text-[#8696a0]">
                        <div class="w-8 h-8 border-2 border-[#00a884] border-t-transparent rounded-full animate-spin"></div>
                        <span class="text-xs text-[#8696a0] mt-3">Memuat pesan...</span>
                    </div>
                </div>
            </div>

            <!-- Scroll to bottom button (WhatsApp style floating pill) -->
            <button id="wa-scroll-btn" onclick="window.waScrollToBottom(true)" class="hidden absolute bottom-4 right-6 z-20 w-10 h-10 rounded-full bg-[#202c33] text-[#8696a0] hover:text-[#e9edef] shadow-lg flex items-center justify-center transition-all hover:scale-105 active:scale-95 border border-[#374248]" title="Gulir ke bawah">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>

        <!-- WhatsApp Input Bar (Bottom) -->
        <div class="bg-[#202c33] px-3 sm:px-4 py-2.5 shrink-0 z-20 border-t border-[#222d34]">
            <div class="max-w-4xl mx-auto">
                <form id="chat-form" class="flex items-center gap-2 sm:gap-3">
                    
                    <!-- Plus (+) Button -->
                    <button type="button" class="text-[#8696a0] hover:text-[#e9edef] p-2 rounded-full hover:bg-[#374248] transition-colors shrink-0" title="Lampirkan">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>

                    <!-- Emoji / Smiley Button -->
                    <button type="button" class="text-[#8696a0] hover:text-[#e9edef] p-2 rounded-full hover:bg-[#374248] transition-colors shrink-0" title="Emoji">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>

                    <!-- Text Input Pill -->
                    <div class="flex-1 bg-[#2a3942] rounded-lg px-3.5 py-1.5 flex items-center min-h-[42px] max-h-32 transition-colors">
                        <textarea 
                            id="message-input" 
                            name="message" 
                            class="w-full bg-transparent border-0 focus:ring-0 focus:outline-none text-[#d1d7db] placeholder-[#8696a0] resize-none text-[15px] leading-5 p-0 select-text" 
                            rows="1"
                            placeholder="Ketik pesan" 
                            required
                            style="min-height: 24px; max-height: 100px;"
                        ></textarea>
                    </div>

                    <!-- Voice Note / Send Button -->
                    <button 
                        type="submit" 
                        id="send-btn"
                        class="text-[#8696a0] hover:text-[#00a884] p-2 rounded-full hover:bg-[#374248] transition-colors shrink-0 flex items-center justify-center cursor-pointer"
                        title="Kirim (Enter)"
                    >
                        <!-- Microphone Icon (shown when input is empty) -->
                        <svg id="wa-mic-icon" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                        </svg>
                        <!-- Send Paperplane Icon (shown when input has text) -->
                        <svg id="wa-send-icon" class="w-6 h-6 hidden text-[#00a884]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M1.101 21.757L23.8 12.028 1.101 2.3l.011 7.912 13.623 1.816-13.623 1.817-.011 7.912z"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Contact Info Modal -->
        <div id="wa-contact-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs" onclick="if(event.target === this) this.classList.add('hidden')">
            <div class="bg-[#111b21] border border-[#222d34] rounded-xl shadow-2xl p-6 max-w-sm w-full text-center relative text-[#e9edef]">
                <button type="button" onclick="document.getElementById('wa-contact-modal').classList.add('hidden')" class="absolute top-4 right-4 text-[#8696a0] hover:text-[#e9edef]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                @if($receiver->has_profile_photo)
                    <img src="{{ asset('storage/' . $receiver->profile_photo_path) }}" alt="{{ $receiver->name }}" class="w-24 h-24 rounded-full object-cover mx-auto mb-3 border-2 border-[#00a884]">
                @else
                    <div class="w-24 h-24 rounded-full bg-[#5c255c] flex items-center justify-center text-white font-bold text-3xl mx-auto mb-3">
                        {{ strtoupper(substr($receiver->name, 0, 1)) }}
                    </div>
                @endif
                <h3 class="text-lg font-bold text-[#e9edef]">{{ $receiver->name }}</h3>
                <span class="inline-block mt-1 px-3 py-0.5 rounded-full text-xs font-semibold bg-[#202c33] text-[#00a884] border border-[#2a3942]">
                    {{ $receiver->role_label }}
                </span>
                @if($receiver->satker)
                    <p class="text-xs text-[#8696a0] mt-2">{{ $receiver->satker->nama_satker }}</p>
                @endif
                @if($receiver->no_hp)
                    <p class="text-xs text-[#00a884] mt-2">📞 {{ $receiver->no_hp }}</p>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    (function() {
        const receiverId = {{ $receiver->id }};
        const currentUserId = {{ auth()->id() }};
        const messagesUrl = "{{ route('chat.messages', $receiver->id) }}";
        const storeUrl = "{{ route('chat.store', $receiver->id) }}";
        const csrfToken = "{{ csrf_token() }}";
        const receiverName = "{{ addslashes($receiver->name) }}";

        let chatContainer = null;
        let chatStreamWrapper = null;
        let chatForm = null;
        let messageInput = null;
        let sendBtn = null;
        let micIcon = null;
        let sendIcon = null;
        let scrollBtn = null;
        let lastMessageCount = -1;
        let isFirstLoad = true;

        window.waScrollToBottom = function(smooth = false) {
            if (!chatContainer) return;
            chatContainer.scrollTo({
                top: chatContainer.scrollHeight,
                behavior: smooth ? 'smooth' : 'auto'
            });
        };

        function initChatRoom() {
            chatContainer = document.getElementById('chat-messages');
            chatStreamWrapper = document.getElementById('chat-stream-wrapper');
            chatForm = document.getElementById('chat-form');
            messageInput = document.getElementById('message-input');
            sendBtn = document.getElementById('send-btn');
            micIcon = document.getElementById('wa-mic-icon');
            sendIcon = document.getElementById('wa-send-icon');
            scrollBtn = document.getElementById('wa-scroll-btn');

            if (!chatContainer || !chatForm || !messageInput || !sendBtn) return;

            let isSendingMessage = false;

            function sendMessage() {
                if (isSendingMessage) return;
                const message = messageInput ? messageInput.value.trim() : '';
                if (!message) return;

                isSendingMessage = true;
                messageInput.disabled = true;
                sendBtn.disabled = true;

                fetch(storeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ message: message })
                })
                .then(r => r.json())
                .then(() => {
                    messageInput.value = '';
                    messageInput.style.height = '24px';
                    if (micIcon) micIcon.classList.remove('hidden');
                    if (sendIcon) sendIcon.classList.add('hidden');
                    fetchMessages(true);
                })
                .catch(err => {
                    console.error('Error sending message:', err);
                    if (window.showAlert) {
                        window.showAlert('Gagal', 'Tidak dapat mengirim pesan.', 'error');
                    } else {
                        alert('Gagal mengirim pesan.');
                    }
                })
                .finally(() => {
                    messageInput.disabled = false;
                    sendBtn.disabled = false;
                    messageInput.focus();
                    isSendingMessage = false;
                });
            }

            // Auto-resize textarea & toggle mic/send icon using property assignment (no duplicate event listeners)
            messageInput.oninput = function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 100) + 'px';

                if (this.value.trim().length > 0) {
                    if (micIcon) micIcon.classList.add('hidden');
                    if (sendIcon) sendIcon.classList.remove('hidden');
                } else {
                    if (micIcon) micIcon.classList.remove('hidden');
                    if (sendIcon) sendIcon.classList.add('hidden');
                    this.style.height = '24px';
                }
            };

            // Handle Enter key directly without dispatching submit event
            messageInput.onkeydown = function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    e.stopPropagation();
                    sendMessage();
                    return false;
                }
            };

            // Form submit handler using onsubmit property
            chatForm.onsubmit = function(e) {
                e.preventDefault();
                e.stopPropagation();
                sendMessage();
                return false;
            };

            // Scroll button visibility
            chatContainer.onscroll = function() {
                if (!scrollBtn) return;
                const isNearBottom = (chatContainer.scrollHeight - chatContainer.scrollTop) <= (chatContainer.clientHeight + 200);
                if (isNearBottom) {
                    scrollBtn.classList.add('hidden');
                } else {
                    scrollBtn.classList.remove('hidden');
                }
            };

            // Focus textarea
            setTimeout(() => {
                if (messageInput) {
                    messageInput.focus();
                    window.waScrollToBottom(false);
                }
            }, 100);

            // Fetch initial messages
            fetchMessages();

            // Clear previous interval if any
            if (window.activeChatRoomPoll) {
                clearInterval(window.activeChatRoomPoll);
            }

            // Start polling every 2.5 seconds
            window.activeChatRoomPoll = setInterval(() => {
                fetchMessages();
            }, 2500);
        }

        function formatTime(dateString) {
            const date = new Date(dateString);
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${hours}.${minutes}`;
        }

        function formatDateWhatsApp(dateString) {
            const date = new Date(dateString);
            return `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}`;
        }

        function formatTextWithLinks(text) {
            if (!text) return '';
            // Auto link URLs
            const urlRegex = /(https?:\/\/[^\s]+)/g;
            return text.replace(urlRegex, function(url) {
                return `<a href="${url}" target="_blank" rel="noopener noreferrer" class="text-[#53bdeb] underline hover:text-[#79caff] break-all">${url}</a>`;
            });
        }

        function fetchMessages(forceScroll = false) {
            if (!chatContainer) return;

            fetch(messagesUrl)
                .then(r => r.json())
                .then(messages => {
                    if (!Array.isArray(messages)) return;

                    const isAtBottom = (chatContainer.scrollHeight - chatContainer.scrollTop) <= (chatContainer.clientHeight + 160);

                    if (messages.length !== lastMessageCount || isFirstLoad) {
                        renderMessages(messages);
                        lastMessageCount = messages.length;

                        if (isFirstLoad || forceScroll || isAtBottom) {
                            setTimeout(() => window.waScrollToBottom(forceScroll ? true : !isFirstLoad), 50);
                            isFirstLoad = false;
                        }
                    }
                })
                .catch(err => {
                    console.error('Error fetching chat messages:', err);
                });
        }

        function renderMessages(messages) {
            const targetContainer = chatStreamWrapper || chatContainer;
            if (!targetContainer) return;

            if (messages.length === 0) {
                targetContainer.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-20 text-[#8696a0]">
                        <div class="px-4 py-2 bg-[#182229] border border-[#222d34] rounded-lg text-xs text-[#8696a0] shadow-sm mb-4">
                            🔒 Pesan dan panggilan diamankan dalam sistem SPBP Polda NTB.
                        </div>
                        <p class="text-xs text-[#8696a0]">Kirim pesan kepada <span class="text-[#e9edef] font-medium">${receiverName}</span> untuk memulai percakapan.</p>
                    </div>
                `;
                return;
            }

            let html = '';
            let lastDate = null;

            messages.forEach((msg) => {
                const isMe = msg.sender_id === currentUserId;
                const msgDate = formatDateWhatsApp(msg.created_at);

                // Date Badge (WhatsApp Style)
                if (msgDate !== lastDate) {
                    html += `
                        <div class="flex justify-center my-3">
                            <span class="px-3 py-1 bg-[#182229] border border-[#222d34] rounded-lg text-[12px] font-normal text-[#8696a0] shadow-sm select-none">
                                ${msgDate}
                            </span>
                        </div>
                    `;
                    lastDate = msgDate;
                }

                // WhatsApp Bubble Colors:
                // Me: #005c4b (WhatsApp Dark Green)
                // Other: #202c33 (WhatsApp Dark Card)
                const bubbleBg = isMe ? 'bg-[#005c4b] text-[#e9edef]' : 'bg-[#202c33] text-[#e9edef]';
                const cornerClass = isMe ? 'rounded-lg rounded-tr-none' : 'rounded-lg rounded-tl-none';

                // WhatsApp Double Checkmark (Cyan blue #53bdeb if read, #8696a0 if sent)
                const checkStatus = isMe ? (msg.is_read 
                    ? '<svg class="w-4 h-4 text-[#53bdeb] ml-1 inline" viewBox="0 0 16 15" fill="none"><path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.879a.32.32 0 0 1-.484.033l-.358-.325a.319.319 0 0 0-.484.032l-.378.483a.418.418 0 0 0 .036.541l1.32 1.266c.143.14.361.125.484-.033l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.879a.32.32 0 0 1-.484.033L1.891 7.769a.366.366 0 0 0-.515.006l-.423.433a.364.364 0 0 0 .006.514l3.258 3.185c.143.14.361.125.484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="currentColor"/></svg>' 
                    : '<svg class="w-3.5 h-3.5 text-[#8696a0] ml-1 inline" viewBox="0 0 16 15" fill="none"><path d="M10.91 3.316l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.879a.32.32 0 0 1-.484.033L1.891 7.769a.366.366 0 0 0-.515.006l-.423.433a.364.364 0 0 0 .006.514l3.258 3.185c.143.14.361.125.484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="currentColor"/></svg>') : '';

                // Formatted content
                const formattedMessage = formatTextWithLinks(msg.message);

                html += `
                    <div class="flex w-full ${isMe ? 'justify-end' : 'justify-start'} my-1 group animate-fadeIn">
                        <div class="relative flex items-center gap-1.5 max-w-[85%] sm:max-w-[75%] md:max-w-[65%] ${isMe ? 'flex-row' : 'flex-row-reverse'}">
                            
                            <!-- Delete Button (Hover Action) -->
                            ${isMe ? `
                                <button onclick="window.deleteChatMessage(${msg.id})" class="opacity-0 group-hover:opacity-100 transition-opacity p-1 text-[#8696a0] hover:text-red-400 rounded-full shrink-0" title="Hapus pesan">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            ` : ''}

                            <!-- WhatsApp Chat Bubble -->
                            <div class="${bubbleBg} ${cornerClass} px-3 py-1.5 text-[14.2px] leading-[19px] shadow-[0_1px_0.5px_rgba(11,20,26,0.13)] w-fit min-w-[85px] relative break-words select-text">
                                <div class="pr-2 inline">
                                    ${formattedMessage}
                                </div>
                                <span class="inline-flex items-center float-right ml-2 mt-1 select-none whitespace-nowrap text-[11px] text-[#8696a0] leading-none">
                                    ${formatTime(msg.created_at)}
                                    ${checkStatus}
                                </span>
                            </div>
                        </div>
                    </div>
                `;
            });

            targetContainer.innerHTML = html;
        }

        window.deleteChatMessage = function(chatId) {
            const doDelete = () => {
                fetch(`/chat/${chatId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        fetchMessages(false);
                    } else if (window.showAlert) {
                        window.showAlert('Gagal', res.error || 'Tidak dapat menghapus pesan', 'error');
                    }
                })
                .catch(err => {
                    console.error('Error delete chat:', err);
                });
            };

            if (window.confirmDialog) {
                window.confirmDialog({
                    title: 'Hapus Pesan?',
                    message: 'Apakah Anda ingin menghapus pesan ini?',
                    type: 'warning',
                    confirmText: 'Hapus untuk Semua Orang'
                }, doDelete);
            } else if (confirm('Apakah Anda yakin ingin menghapus pesan ini?')) {
                doDelete();
            }
        };

        // Initialize when ready or via Turbo
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initChatRoom);
        } else {
            initChatRoom();
        }

        document.addEventListener('turbo:load', initChatRoom);

        document.addEventListener('turbo:before-visit', function() {
            if (window.activeChatRoomPoll) {
                clearInterval(window.activeChatRoomPoll);
                window.activeChatRoomPoll = null;
            }
        });
    })();
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.15s ease-out forwards;
        }
        
        #chat-messages::-webkit-scrollbar {
            width: 6px;
        }
        #chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }
        #chat-messages::-webkit-scrollbar-thumb {
            background: rgba(134, 150, 160, 0.2); 
            border-radius: 10px;
        }
        #chat-messages::-webkit-scrollbar-thumb:hover {
            background: rgba(134, 150, 160, 0.35); 
        }
    </style>
    @endpush
</x-app-layout>
