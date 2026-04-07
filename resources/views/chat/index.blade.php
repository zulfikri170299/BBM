<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-primary to-blue-600 bg-clip-text text-transparent">
                    Chat & Konsultasi</h1>
                <p class="text-slate-500 mt-1">Hubungi rekan kerja atau admin untuk konsultasi.</p>
            </div>
            <!-- Search bar could go here or inside the card -->
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <!-- Header & Search -->
            <div
                class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50">
                <h3 class="font-bold text-lg text-slate-800">Daftar Kontak</h3>
                <div class="relative w-full sm:w-72">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" id="search-contact"
                        class="pl-10 block w-full rounded-xl border-slate-300 bg-white shadow-sm focus:border-primary focus:ring focus:ring-primary/20 sm:text-sm transition-shadow"
                        placeholder="Cari nama atau role...">
                </div>
            </div>

            <!-- Contact Grid -->
            <div class="p-6 bg-slate-50/30">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="contact-list">
                    @foreach ($users as $u)
                        <a href="{{ route('chat.show', $u->id) }}"
                            class="contact-card group relative bg-white border border-slate-200 rounded-xl p-3.5 hover:border-primary/40 hover:shadow-lg hover:shadow-primary/5 transition-all duration-300 flex items-center space-x-3.5">
                            <!-- Avatar -->
                            <div class="flex-shrink-0 relative">
                                <div
                                    class="w-11 h-11 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 text-slate-600 flex items-center justify-center text-lg font-bold shadow-inner group-hover:from-primary group-hover:to-blue-600 group-hover:text-white transition-all duration-300">
                                    {{ substr($u->name, 0, 1) }}
                                </div>
                                @if($u->isOnline())
                                    <span
                                        class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full shadow-sm"
                                        title="Online"></span>
                                @endif
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-sm font-bold text-slate-800 truncate group-hover:text-primary transition-colors mb-0.5">
                                    {{ $u->name }}
                                </p>
                                <p
                                    class="text-[11px] font-semibold text-indigo-600 truncate contact-role uppercase tracking-wider">
                                    {{ $u->role_label }}
                                </p>
                                @if($u->satker)
                                    <p class="text-[10px] text-slate-400 truncate contact-satker leading-tight mt-0.5"
                                        title="{{ $u->satker->nama_satker }}">
                                        {{ $u->satker->nama_satker }}
                                    </p>
                                @endif

                                <!-- Unread Badge -->
                                @if(auth()->user()->receivedChats()->where('sender_id', $u->id)->where('is_read', false)->exists())
                                    <div class="mt-2 flex items-center scale-100 transition-transform origin-left">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 shadow-sm border border-red-200 animate-pulse">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                            Pesan Baru
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Arrow Icon -->
                            <div
                                class="text-slate-300 group-hover:text-primary group-hover:translate-x-1 transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Empty State for Search -->
                <div id="no-results" class="hidden flex-col items-center justify-center py-12 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900">Tidak ada kontak ditemukan</h3>
                    <p class="text-slate-500 mt-1">Coba kata kunci pencarian lain.</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('turbo:load', function () {
                const searchInput = document.getElementById('search-contact');
                const contactList = document.getElementById('contact-list');
                const noResults = document.getElementById('no-results');
                
                if (!searchInput || !contactList || !noResults) return;

                // Remove existing listeners to avoid duplicates on Turbo navigation
                const newSearchInput = searchInput.cloneNode(true);
                searchInput.parentNode.replaceChild(newSearchInput, searchInput);

                newSearchInput.addEventListener('input', function (e) {
                    const term = e.target.value.toLowerCase();
                    const contacts = document.querySelectorAll('.contact-card');
                    let visibleCount = 0;

                    contacts.forEach(card => {
                        const nameEl = card.querySelector('.text-sm.font-bold');
                        const roleEl = card.querySelector('.contact-role');
                        const satkerEl = card.querySelector('.contact-satker');
                        
                        const name = nameEl ? nameEl.textContent.toLowerCase() : '';
                        const role = roleEl ? roleEl.textContent.toLowerCase() : '';
                        const satker = satkerEl ? satkerEl.textContent.toLowerCase() : '';

                        if (name.includes(term) || role.includes(term) || satker.includes(term)) {
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
                });
            });
        </script>
    @endpush
</x-app-layout>