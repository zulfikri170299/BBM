<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center border border-indigo-100 dark:border-indigo-500/20 shadow-sm shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800 dark:text-white tracking-tight">
                        Chat & Konsultasi Internal
                    </h1>
                    <p class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400 mt-0.5">
                        Hubungi rekan kerja, petugas SPBP, atau administrator untuk koordinasi BBM.
                    </p>
                </div>
            </div>

            <!-- Header Status Badges -->
            @php
                $totalUnreadAll = $users->sum('unread_count');
                $onlineCount = $users->filter(fn($usr) => $usr->isOnline())->count();
            @endphp
            <div class="flex flex-wrap items-center gap-2">
                @if($totalUnreadAll > 0)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20 shadow-sm animate-pulse">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        {{ $totalUnreadAll }} Pesan Belum Dibaca
                    </span>
                @endif
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    {{ $onlineCount }} Online
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-white/10 shadow-sm">
                    {{ $users->count() }} Kontak
                </span>
            </div>
        </div>

        <!-- Contact Directory Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
            <!-- Header Toolbar: Tabs & Search -->
            <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-white/10 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 bg-slate-50/70 dark:bg-slate-800/40">
                <!-- Filter Tabs -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0" id="filter-tabs">
                    <button type="button" data-filter="all"
                        class="tab-btn active px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 bg-indigo-600 text-white shadow-sm shadow-indigo-600/20">
                        <span>Semua</span>
                        <span class="px-1.5 py-0.5 rounded-md text-[10px] bg-white/20">{{ $users->count() }}</span>
                    </button>
                    <button type="button" data-filter="online"
                        class="tab-btn px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-white/10">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Online</span>
                        <span class="px-1.5 py-0.5 rounded-md text-[10px] bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">{{ $onlineCount }}</span>
                    </button>
                    @if($totalUnreadAll > 0)
                        <button type="button" data-filter="unread"
                            class="tab-btn px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 bg-white dark:bg-slate-800 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 border border-rose-200 dark:border-rose-500/20">
                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                            <span>Pesan Baru</span>
                            <span class="px-1.5 py-0.5 rounded-md text-[10px] bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300">{{ $totalUnreadAll }}</span>
                        </button>
                    @endif
                </div>

                <!-- Instant Search Input -->
                <div class="relative w-full md:w-80">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="search-contact"
                        style="padding-left: 2.35rem !important;"
                        class="w-full h-10 !pl-9 pr-4 rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 text-xs font-semibold text-slate-800 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition"
                        placeholder="Cari nama, role, atau satker...">
                </div>
            </div>

            <!-- Contact Grid -->
            <div class="p-4 sm:p-6 bg-slate-50/40 dark:bg-slate-950/40">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3.5" id="contact-list">
                    @foreach ($users as $u)
                        @php
                            $isOnline = $u->isOnline();
                            $hasUnread = $u->unread_count > 0;
                            
                            // Role styling badges
                            $badgeStyle = match($u->role) {
                                'super_admin' => 'bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400 border-purple-200 dark:border-purple-500/20',
                                'kasubbag' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border-blue-200 dark:border-blue-500/20',
                                'admin_satker' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 border-indigo-200 dark:border-indigo-500/20',
                                'petugas_bbm' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border-amber-200 dark:border-amber-500/20',
                                'personel' => 'bg-cyan-50 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-400 border-cyan-200 dark:border-cyan-500/20',
                                default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-200 dark:border-white/10'
                            };

                            // Role avatar gradient
                            $avatarGrad = match($u->role) {
                                'super_admin' => 'from-purple-600 to-indigo-600',
                                'kasubbag' => 'from-blue-600 to-cyan-600',
                                'admin_satker' => 'from-indigo-600 to-blue-600',
                                'petugas_bbm' => 'from-amber-500 to-orange-600',
                                'personel' => 'from-emerald-500 to-teal-600',
                                default => 'from-slate-600 to-slate-700'
                            };

                            // Short initials (2 chars)
                            $words = preg_split('/\s+/', trim($u->name));
                            $initials = '';
                            if (count($words) >= 2) {
                                $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                            } else {
                                $initials = strtoupper(substr($u->name, 0, min(2, strlen($u->name))));
                            }
                        @endphp

                        <a href="{{ route('chat.show', $u->id) }}"
                            data-online="{{ $isOnline ? 'true' : 'false' }}"
                            data-unread="{{ $hasUnread ? 'true' : 'false' }}"
                            class="contact-card group relative bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/90 dark:border-white/10 p-3.5 hover:border-indigo-500/50 hover:shadow-lg hover:shadow-indigo-500/5 dark:hover:border-indigo-500/40 transition-all duration-200 flex items-center justify-between gap-3">
                            
                            <!-- Left: Avatar with Online Indicator -->
                            <div class="relative shrink-0">
                                @if($u->has_profile_photo)
                                    <img src="{{ $u->profile_photo_url }}" alt="{{ $u->name }}"
                                        class="w-11 h-11 rounded-xl object-cover ring-2 ring-slate-200 dark:ring-white/10"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div style="display: none;" class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $avatarGrad }} text-white flex items-center justify-center text-xs font-black shadow-sm tracking-wider">
                                        {{ $initials }}
                                    </div>
                                @else
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $avatarGrad }} text-white flex items-center justify-center text-xs font-black shadow-sm tracking-wider">
                                        {{ $initials }}
                                    </div>
                                @endif

                                @if($isOnline)
                                    <span class="absolute -bottom-0.5 -right-0.5 flex h-3.5 w-3.5" title="Online">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500 border-2 border-white dark:border-slate-900"></span>
                                    </span>
                                @else
                                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-slate-300 dark:bg-slate-600 border-2 border-white dark:border-slate-900 rounded-full" title="Offline"></span>
                                @endif
                            </div>

                            <!-- Center: User Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <h4 class="contact-name text-xs sm:text-sm font-bold text-slate-800 dark:text-white truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors"
                                        title="{{ $u->name }}">
                                        {{ $u->name }}
                                    </h4>
                                </div>

                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="contact-role inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider border {{ $badgeStyle }}">
                                        {{ $u->role_label }}
                                    </span>

                                    @if($hasUnread)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[9px] font-black bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20 animate-pulse">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            {{ $u->unread_count }} baru
                                        </span>
                                    @endif
                                </div>

                                @if($u->satker && strtolower($u->satker->nama_satker) !== strtolower($u->name))
                                    <p class="contact-satker text-[10px] text-slate-500 dark:text-slate-400 truncate mt-1 flex items-center gap-1"
                                        title="{{ $u->satker->nama_satker }}">
                                        <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="truncate">{{ $u->satker->nama_satker }}</span>
                                    </p>
                                @endif
                            </div>

                            <!-- Right: Chevron Action -->
                            <div class="w-7 h-7 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-400 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-500/20 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 group-hover:translate-x-0.5 flex items-center justify-center shrink-0 transition-all border border-slate-200/60 dark:border-white/5">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Empty State for Search / Filter -->
                <div id="no-results" class="hidden flex-col items-center justify-center py-16 text-center">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800/60 rounded-2xl flex items-center justify-center mb-3 text-slate-400 border border-slate-200 dark:border-white/10">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800 dark:text-white">Kontak tidak ditemukan</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-sm">
                        Tidak ada rekan kerja yang cocok dengan kata kunci atau filter yang Anda pilih.
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('turbo:load', setupContactFilter);
            document.addEventListener('DOMContentLoaded', setupContactFilter);

            function setupContactFilter() {
                const searchInput = document.getElementById('search-contact');
                const contactList = document.getElementById('contact-list');
                const noResults = document.getElementById('no-results');
                const tabButtons = document.querySelectorAll('#filter-tabs .tab-btn');
                
                if (!searchInput || !contactList || !noResults) return;

                let currentTab = 'all';

                function filterContacts() {
                    const term = searchInput.value.toLowerCase().trim();
                    const cards = document.querySelectorAll('.contact-card');
                    let visibleCount = 0;

                    cards.forEach(card => {
                        const nameEl = card.querySelector('.contact-name');
                        const roleEl = card.querySelector('.contact-role');
                        const satkerEl = card.querySelector('.contact-satker');
                        
                        const name = nameEl ? nameEl.textContent.toLowerCase() : '';
                        const role = roleEl ? roleEl.textContent.toLowerCase() : '';
                        const satker = satkerEl ? satkerEl.textContent.toLowerCase() : '';

                        const matchesSearch = term === '' || name.includes(term) || role.includes(term) || satker.includes(term);
                        
                        let matchesTab = true;
                        if (currentTab === 'online') {
                            matchesTab = card.getAttribute('data-online') === 'true';
                        } else if (currentTab === 'unread') {
                            matchesTab = card.getAttribute('data-unread') === 'true';
                        }

                        if (matchesSearch && matchesTab) {
                            card.style.display = 'flex';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    if (visibleCount === 0) {
                        contactList.classList.add('hidden');
                        noResults.classList.remove('hidden');
                        noResults.classList.add('flex');
                    } else {
                        contactList.classList.remove('hidden');
                        noResults.classList.add('hidden');
                        noResults.classList.remove('flex');
                    }
                }

                // Search Event
                searchInput.addEventListener('input', filterContacts);

                // Tab Click Event
                tabButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        tabButtons.forEach(b => {
                            b.classList.remove('bg-indigo-600', 'text-white', 'shadow-sm', 'shadow-indigo-600/20');
                            b.classList.add('bg-white', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300');
                        });
                        this.classList.remove('bg-white', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300');
                        this.classList.add('bg-indigo-600', 'text-white', 'shadow-sm', 'shadow-indigo-600/20');

                        currentTab = this.getAttribute('data-filter');
                        filterContacts();
                    });
                });
            }
        </script>
    @endpush
</x-app-layout>