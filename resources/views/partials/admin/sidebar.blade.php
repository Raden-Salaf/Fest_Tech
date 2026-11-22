<aside class="w-full shrink-0 bg-[#06251D] text-white md:min-h-screen md:w-64">

    {{-- Identitas aplikasi --}}
    <div class="flex h-[88px] items-center border-b border-white/10 px-6">
        <a href="{{ route('admin.dashboard') }}" class="block">
            <p class="text-xl font-extrabold tracking-wide">
                FEST<span class="text-emerald-400">.TECH</span>
            </p>
            <p class="mt-1 text-xs text-emerald-200">
                Administrator Panel
            </p>
        </a>
    </div>

    <nav class="p-4">
        <p class="mb-3 px-3 text-xs font-bold uppercase tracking-wider text-emerald-300">
            Menu Utama
        </p>

        <ul class="flex flex-wrap gap-2 md:flex-col">

            {{-- Dashboard --}}
            <li>
                <a
                    href="{{ route('admin.dashboard') }}"
                    @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif
                    class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-700 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >
                    <span aria-hidden="true">▦</span>
                    Dashboard
                </a>
            </li>

            {{-- Kompetisi --}}
            <li>
                <a
                    href="{{ route('admin.competitions.index') }}"
                    @if (request()->routeIs('admin.competitions.*')) aria-current="page" @endif
                    class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.competitions.*') ? 'bg-emerald-700 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >
                    <span aria-hidden="true">🏆</span>
                    Kompetisi
                </a>
            </li>

            {{-- Berita --}}
            <li>
                <a
                    href="{{ route('admin.news.index') }}"
                    @if (request()->routeIs('admin.news.*')) aria-current="page" @endif
                    class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.news.*') ? 'bg-emerald-700 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >
                    <span aria-hidden="true">📰</span>
                    Berita
                </a>
            </li>

            {{-- Peserta Kompetisi --}}
            <li>
                <a
                    href="{{ route('admin.participants.index') }}"
                    @if (request()->routeIs('admin.participants.*')) aria-current="page" @endif
                    class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.participants.*') ? 'bg-emerald-700 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >
                    <span aria-hidden="true">👥</span>
                    Peserta Kompetisi
                </a>
            </li>

            {{-- Users --}}
            <li>
                <a
                    href="{{ route('admin.users.index') }}"
                    @if (request()->routeIs('admin.users.*')) aria-current="page" @endif
                    class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.users.*') ? 'bg-emerald-700 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >
                    <span aria-hidden="true">👤</span>
                    Users
                </a>
            </li>

            {{-- Pembayaran --}}
            <li>
                <a
                    href="{{ route('admin.payments.index') }}"
                    @if (request()->routeIs('admin.payments.*')) aria-current="page" @endif
                    class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.payments.*') ? 'bg-emerald-700 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >
                    <span aria-hidden="true">💳</span>
                    Pembayaran
                </a>
            </li>

            {{-- Sponsor --}}
            <li>
                <span class="flex cursor-not-allowed items-center gap-3 rounded-lg px-3 py-3 text-sm text-slate-400">
                    <span aria-hidden="true">🤝</span>
                    Sponsor
                    <span class="ml-auto text-[10px]">Segera</span>
                </span>
            </li>

        </ul>

        {{-- Sistem --}}
        <p class="mb-3 mt-8 px-3 text-xs font-bold uppercase tracking-wider text-emerald-300">
            Sistem
        </p>

        <ul>
            <li>
                <span class="flex cursor-not-allowed items-center gap-3 rounded-lg px-3 py-3 text-sm text-slate-400">
                    <span aria-hidden="true">⚙</span>
                    Pengaturan
                    <span class="ml-auto text-[10px]">Segera</span>
                </span>
            </li>
        </ul>

    </nav>
</aside>
