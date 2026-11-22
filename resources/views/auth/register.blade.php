@extends('layouts.app')

@section('title', 'Daftar Akun - Festival Teknologi UNUSIA')

@section('content')
<main class="relative flex min-h-[calc(100vh-80px)] items-center overflow-hidden bg-[#06251D] px-5 py-12 text-white md:px-10 lg:px-16">

    {{-- Background video --}}
    <video
        class="pointer-events-none absolute inset-0 z-0 h-full w-full object-cover"
        autoplay
        muted
        loop
        playsinline
        aria-hidden="true"
    >
        <source src="{{ asset('videos/hero-festival.mp4') }}" type="video/mp4">
    </video>

    {{-- Overlay untuk menjaga kontras teks --}}
    <div class="pointer-events-none absolute inset-0 z-0 bg-[#031A14]/85"></div>

    {{-- Ambient lighting --}}
    <div class="pointer-events-none absolute -left-40 top-10 z-0 h-96 w-96 rounded-full bg-emerald-500/20 blur-[120px]"></div>
    <div class="pointer-events-none absolute -bottom-40 right-0 z-0 h-96 w-96 rounded-full bg-blue-500/20 blur-[120px]"></div>

    <div class="relative z-10 mx-auto grid w-full max-w-6xl items-center gap-12 lg:grid-cols-2 lg:gap-20">

        {{-- Branding desktop --}}
        <section class="hidden lg:block">

            {{-- Logo transparan tanpa kotak putih --}}
            <div class="mb-10 inline-flex items-center gap-4">

                <img
                    src="{{ asset('images/logo-unusia.png') }}"
                    alt="Logo UNUSIA"
                    width="56"
                    height="56"
                    class="h-14 w-14 object-contain"
                >

                <div class="h-12 w-px bg-white/30"></div>

                <img
                    src="{{ asset('images/logo-festival.png') }}"
                    alt="Logo Festival Teknologi"
                    width="190"
                    height="56"
                    class="h-10 w-auto max-w-[190px] object-contain"
                >

            </div>

            <p class="mb-5 text-sm font-bold uppercase tracking-[0.35em] text-emerald-400">
                Be Part of the Innovation
            </p>

            <h1 class="max-w-xl text-5xl font-black leading-tight tracking-tight xl:text-6xl">
                JOIN THE
                <span class="block bg-gradient-to-r from-emerald-400 to-blue-400 bg-clip-text text-transparent">
                    NEXT GENERATION.
                </span>
            </h1>

            <p class="mt-7 max-w-lg text-lg leading-relaxed text-slate-300">
                Buat akun dan jadilah bagian dari Festival Teknologi UNUSIA.
                Temukan pengalaman baru, tunjukkan kreativitas, dan
                tantang kemampuanmu melalui kompetisi teknologi.
            </p>

            <div class="mt-10 flex items-center gap-4">
                <div class="h-px w-16 bg-emerald-400"></div>

                <span class="text-xs font-bold uppercase tracking-[0.3em] text-slate-300">
                    Create · Innovate · Compete
                </span>
            </div>

        </section>

        {{-- Register form --}}
        <section class="mx-auto w-full max-w-md">

            <div class="rounded-2xl border border-white/15 bg-[#06251D]/95 p-7 shadow-2xl shadow-black/40 backdrop-blur-xl sm:p-9">

                {{-- Branding mobile --}}
                <div class="mb-7 flex items-center justify-center gap-3 lg:hidden">

                    <img
                        src="{{ asset('images/logo-unusia.png') }}"
                        alt="Logo UNUSIA"
                        width="48"
                        height="48"
                        class="h-12 w-12 object-contain"
                    >

                    <div class="h-10 w-px bg-white/30"></div>

                    <img
                        src="{{ asset('images/logo-festival.png') }}"
                        alt="Logo Festival Teknologi"
                        width="140"
                        height="48"
                        class="h-9 w-auto max-w-[140px] object-contain"
                    >

                </div>

                {{-- Form header --}}
                <div class="mb-7">
                    <p class="mb-3 text-xs font-bold uppercase tracking-[0.3em] text-emerald-400">
                        Create Account
                    </p>

                    <h2 class="text-3xl font-black tracking-tight sm:text-4xl">
                        Join Us<span class="text-emerald-400">.</span>
                    </h2>

                    <p class="mt-3 text-sm leading-relaxed text-slate-300">
                        Daftarkan dirimu untuk mengikuti kompetisi Festival Teknologi UNUSIA.
                    </p>
                </div>

                {{-- Validation errors --}}
                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-400/30 bg-red-500/10 p-4 text-sm text-red-300">
                        <p class="mb-2 font-bold">Periksa kembali data berikut:</p>

                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Registration form --}}
                <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Full name --}}
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-slate-200">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autocomplete="name"
                            placeholder="Masukkan nama lengkap"
                            class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3.5 text-white outline-none transition placeholder:text-slate-500 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-400/20"
                        >

                        @error('name')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-200">
                            Email Address
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            placeholder="nama@email.com"
                            class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3.5 text-white outline-none transition placeholder:text-slate-500 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-400/20"
                        >

                        @error('email')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-slate-200">
                            Password
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                            class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3.5 text-white outline-none transition placeholder:text-slate-500 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-400/20"
                        >

                        @error('password')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password confirmation --}}
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-200">
                            Konfirmasi Password
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Ulangi password"
                            class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3.5 text-white outline-none transition placeholder:text-slate-500 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-400/20"
                        >
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="group flex w-full items-center justify-center gap-3 rounded-lg bg-[#087B48] px-6 py-4 font-bold text-white shadow-lg shadow-emerald-950/30 transition duration-300 hover:-translate-y-0.5 hover:bg-[#0A9659]"
                    >
                        Buat Akun

                        <span class="transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true">
                            →
                        </span>
                    </button>

                </form>

                <div class="my-7 h-px bg-white/10"></div>

                {{-- Login link --}}
                <p class="text-center text-sm text-slate-300">
                    Sudah memiliki akun?

                    <a
                        href="{{ route('login') }}"
                        class="ml-1 font-bold text-emerald-400 transition hover:text-emerald-300"
                    >
                        Login di sini
                    </a>
                </p>

            </div>

            {{-- Copyright --}}
            <p class="mt-6 text-center text-xs tracking-wide text-slate-400">
                © {{ date('Y') }} Festival Teknologi UNUSIA
            </p>

        </section>

    </div>
</main>
@endsection
