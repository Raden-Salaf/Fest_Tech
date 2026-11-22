<header class="relative z-50 border-b border-white/10 bg-[#06251D] text-white shadow-lg shadow-black/10">
    <nav class="mx-auto flex max-w-screen-2xl flex-wrap items-center justify-between gap-4 px-5 py-3 md:px-10 lg:px-16">

        {{-- Brand --}}
        <a
            href="{{ route('home') }}"
            class="inline-flex shrink-0 items-center gap-2.5 transition-opacity duration-200 hover:opacity-80 md:gap-3 cursor-pointer"
            aria-label="Festival Technology UNUSIA - Beranda"
        >
            <img
                src="{{ asset('images/logo-unusia.png') }}"
                alt="Logo UNUSIA"
                width="44"
                height="44"
                class="h-9 w-9 object-contain md:h-11 md:w-11"
            >

            <span class="h-8 w-px bg-white/30 md:h-10"></span>

            <img
                src="{{ asset('images/logo-festival.png') }}"
                alt="Logo Festival Technology"
                width="180"
                height="36"
                class="h-7 w-auto max-w-[145px] object-contain sm:max-w-[175px] md:h-9 md:max-w-[190px]"
            >
        </a>

        {{-- Hamburger mobile --}}
        <button
            id="menu-toggle"
            type="button"
            class="flex h-10 w-10 items-center justify-center rounded-lg border border-white/20 transition hover:bg-white/10 md:hidden cursor-pointer"
            aria-label="Buka menu"
            aria-expanded="false"
            aria-controls="nav-menu"
        >
            <span class="flex flex-col gap-1.5">
                <span id="line-1" class="block h-0.5 w-6 rounded bg-white transition-all duration-300"></span>
                <span id="line-2" class="block h-0.5 w-6 rounded bg-white transition-all duration-300"></span>
                <span id="line-3" class="block h-0.5 w-6 rounded bg-white transition-all duration-300"></span>
            </span>
        </button>

        {{-- Menu navigasi --}}
        <ul
            id="nav-menu"
            class="hidden w-full flex-col gap-5 border-t border-white/10 py-5 text-sm font-semibold md:flex md:w-auto md:flex-row md:items-center md:gap-8 md:border-0 md:py-0"
        >

            {{-- Home --}}
            <li>
                <a
                    href="{{ route('home') }}"
                    @if (request()->routeIs('home')) aria-current="page" @endif
                    class="block transition-colors duration-200 hover:text-emerald-400 {{ request()->routeIs('home') ? 'text-emerald-400' : 'text-white' }} cursor-pointer"
                >
                    Home
                </a>
            </li>

            {{-- Berita --}}
            <li>
                <a
                    href="{{ route('berita.index') }}"
                    @if (request()->routeIs('berita.*')) aria-current="page" @endif
                    class="block transition-colors duration-200 hover:text-emerald-400 {{ request()->routeIs('berita.*') ? 'text-emerald-400' : 'text-white' }} cursor-pointer"
                >
                    Berita
                </a>
            </li>

            {{-- Kompetisi --}}
            <li>
                <a
                    href="{{ route('kompetisi.index') }}"
                    @if (request()->routeIs('kompetisi.*')) aria-current="page" @endif
                    class="block transition-colors duration-200 hover:text-emerald-400 {{ request()->routeIs('kompetisi.*') ? 'text-emerald-400' : 'text-white' }} cursor-pointer"
                >
                    Kompetisi
                </a>
            </li>

            {{-- Sponsor --}}
            <li>
                <a
                    href="/sponsor"
                    class="block transition-colors duration-200 hover:text-emerald-400 cursor-pointer"
                >
                    Sponsor
                </a>
            </li>

            {{-- Login untuk pengunjung --}}
            @guest
                <li class="pt-2 md:pt-0">
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-emerald-600 px-5 py-2.5 font-bold text-white shadow-lg shadow-emerald-950/30 transition duration-200 hover:-translate-y-0.5 hover:bg-emerald-500 md:w-auto cursor-pointer"
                    >
                        Login
                        <span class="ml-2" aria-hidden="true">→</span>
                    </a>
                </li>
            @endguest

            {{-- Informasi akun pengguna login --}}
            @auth
                <li class="flex flex-col gap-3 border-t border-white/10 pt-4 md:flex-row md:items-center md:gap-4 md:border-0 md:pt-0">

                    {{-- Menu khusus participant --}}
                    @if (auth()->user()->role === 'participant')
                        <a
                            href="{{ route('pendaftaran.index') }}"
                            @if (request()->routeIs('pendaftaran.*')) aria-current="page" @endif
                            class="transition-colors duration-200 hover:text-emerald-400 {{ request()->routeIs('pendaftaran.*') ? 'text-emerald-400' : 'text-white' }} cursor-pointer"
                        >
                            Pendaftaran
                        </a>
                    @endif

                    {{-- Nama pengguna --}}
                    <span class="text-emerald-300">
                        Halo, {{ auth()->user()->name }}
                    </span>

                    {{-- Logout --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-lg border border-white/20 px-5 py-2.5 font-bold text-white transition duration-200 hover:bg-white/10 md:w-auto cursor-pointer"
                        >
                            Logout
                        </button>
                    </form>
                </li>
            @endauth

        </ul>
    </nav>
</header>
