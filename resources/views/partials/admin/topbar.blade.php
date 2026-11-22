<header class="flex h-[88px] flex-wrap items-center justify-between gap-4 border-b border-slate-200 bg-white px-5 shadow-sm md:px-8">

    {{-- Informasi halaman yang sedang dibuka --}}
    <div>
        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">
            Festival Teknologi UNUSIA
        </p>

        <h2 class="mt-1 text-lg font-bold text-slate-900">
            @yield('page-heading', 'Administrator')
        </h2>
    </div>

    {{-- Identitas akun administrator --}}
    <div class="flex items-center gap-3">

        {{-- Avatar sementara menggunakan inisial nama admin --}}
        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-800">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <div class="hidden sm:block">
            <p class="text-sm font-semibold text-slate-800">
                {{ auth()->user()->name }}
            </p>
            <p class="text-xs text-slate-500">
                Administrator
            </p>
        </div>

        {{-- Logout menggunakan POST agar sesuai praktik keamanan Laravel --}}
        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button
                type="submit"
                class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600"
            >
                Logout
            </button>
        </form>

    </div>
</header>
