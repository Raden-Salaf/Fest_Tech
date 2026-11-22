
@extends('layouts.app')

@section('title', 'Home - Festival Teknologi UNUSIA')

@section('content')

{{-- ========================================= --}}
{{-- HERO SECTION --}}
{{-- ========================================= --}}

<section class="relative isolate flex min-h-[calc(100vh-100px)] items-center overflow-hidden bg-[#06251D] px-6 py-24 text-white md:px-16 lg:px-24">

    {{-- Background Video --}}
    <video
        class="absolute inset-0 -z-20 h-full w-full object-cover"
        autoplay
        muted
        loop
        playsinline
        aria-hidden="true"
    >
        <source src="{{ asset('videos/hero-festival.mp4') }}" type="video/mp4">
    </video>

    {{-- Overlay --}}
    <div class="absolute inset-0 -z-10 bg-[#06251D]/85"></div>

    {{-- Dekorasi Background --}}
    <div class="absolute -right-20 top-20 -z-10 h-80 w-80 rounded-full bg-emerald-400/10 blur-3xl"></div>

    <div class="mx-auto w-full max-w-7xl">

        {{-- Label Festival --}}
        <p class="mb-6 text-sm font-bold uppercase tracking-[0.3em] text-[#22C55E]">
            Festival Teknologi UNUSIA
        </p>

        {{-- Headline --}}
        <h1 class="max-w-5xl text-5xl font-black leading-[1.05] tracking-tight sm:text-6xl md:text-7xl lg:text-8xl">
            CREATE.
            <span class="bg-gradient-to-r from-[#22C55E] to-[#2676B9] bg-clip-text text-transparent">
                INNOVATE.
            </span>
            <br>
            COMPETE.
        </h1>

        {{-- Deskripsi --}}
        <p class="mt-8 max-w-2xl text-base leading-relaxed text-slate-300 md:text-lg">
            Wadah kreativitas, inovasi, dan kompetisi teknologi
            untuk melahirkan generasi yang berani berkarya,
            berpikir kritis, dan menciptakan masa depan.
        </p>

        {{-- Tombol CTA --}}
        <div class="mt-10 flex flex-wrap items-center gap-4">

            <a
                href="/kompetisi"
                class="inline-flex items-center gap-3 rounded-md bg-[#087B48] px-7 py-4 font-bold text-white transition duration-300 hover:bg-[#0A9659]"
            >
                Jelajahi Kompetisi
                <span aria-hidden="true">&rarr;</span>
            </a>

            <a
                href="{{ route('berita.index') }}"
                class="inline-flex items-center rounded-md border border-white/30 px-7 py-4 font-semibold text-white transition duration-300 hover:border-[#22C55E] hover:text-[#22C55E]"
            >
                Lihat Berita
            </a>

        </div>

        {{-- Penanda Bagian Bawah --}}
        <div class="mt-20 flex items-center gap-4">

            <div class="h-px w-16 bg-[#22C55E]"></div>

            <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">
                Explore Your Potential
            </span>

        </div>

    </div>

</section>


{{-- ========================================= --}}
{{-- ABOUT SECTION --}}
{{-- ========================================= --}}

<section class="bg-[#F4F7F5] px-6 py-24 text-slate-950 md:px-12 lg:px-16">

    <div class="mx-auto max-w-7xl">

        {{-- Heading --}}
        <div class="grid gap-8 md:grid-cols-2 md:items-end">

            <div>

                <p class="mb-4 text-sm font-bold uppercase tracking-[0.3em] text-[#087B48]">
                    About The Festival
                </p>

                <h2 class="text-4xl font-black leading-tight tracking-tight md:text-5xl">
                    Lebih dari sekadar
                    <span class="text-[#087B48]">kompetisi.</span>
                </h2>

            </div>

            <p class="max-w-xl text-base leading-relaxed text-slate-600 md:text-lg">
                Festival Teknologi UNUSIA menjadi ruang bagi generasi muda
                untuk mengeksplorasi ide, mengembangkan kemampuan, dan
                menunjukkan karya melalui kreativitas serta teknologi.
            </p>

        </div>


        {{-- Tiga Pilar Festival --}}
        <div class="mt-16 grid gap-6 md:grid-cols-3">

            {{-- Pilar 1 --}}
            <article class="border-t-4 border-[#087B48] bg-white p-8 transition duration-300 hover:-translate-y-2 hover:shadow-xl">

                <span class="text-sm font-bold tracking-widest text-[#087B48]">
                    01 / CREATIVITY
                </span>

                <h3 class="mt-8 text-2xl font-black">
                    Kreativitas
                </h3>

                <p class="mt-4 leading-relaxed text-slate-600">
                    Mengembangkan ide-ide segar dan mengubah imajinasi
                    menjadi karya yang memiliki nilai.
                </p>

            </article>


            {{-- Pilar 2 --}}
            <article class="border-t-4 border-[#2676B9] bg-white p-8 transition duration-300 hover:-translate-y-2 hover:shadow-xl">

                <span class="text-sm font-bold tracking-widest text-[#2676B9]">
                    02 / INNOVATION
                </span>

                <h3 class="mt-8 text-2xl font-black">
                    Inovasi
                </h3>

                <p class="mt-4 leading-relaxed text-slate-600">
                    Mendorong pemanfaatan teknologi untuk menciptakan
                    solusi yang relevan bagi masa depan.
                </p>

            </article>


            {{-- Pilar 3 --}}
            <article class="border-t-4 border-[#087B48] bg-white p-8 transition duration-300 hover:-translate-y-2 hover:shadow-xl">

                <span class="text-sm font-bold tracking-widest text-[#087B48]">
                    03 / COMPETITION
                </span>

                <h3 class="mt-8 text-2xl font-black">
                    Kompetisi
                </h3>

                <p class="mt-4 leading-relaxed text-slate-600">
                    Menjadi ruang untuk menguji kemampuan, membangun
                    pengalaman, dan menunjukkan potensi terbaik.
                </p>

            </article>

        </div>

    </div>

</section>

@endsection
