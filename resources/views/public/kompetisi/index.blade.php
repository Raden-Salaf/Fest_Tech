@extends('layouts.app')

@section('title', 'Kompetisi - Festival Teknologi UNUSIA')

@section('content')

<section class="min-h-screen bg-[#F4F7F5] px-6 py-20 md:px-12 lg:px-16">

    <div class="mx-auto max-w-7xl">

        {{-- Header halaman --}}
        <div class="mb-12 text-center">
            <p class="mb-3 text-sm font-bold uppercase tracking-[0.3em] text-[#087B48]">
                Festival Teknologi UNUSIA
            </p>

            <h1 class="text-4xl font-black text-[#06251D] md:text-5xl">
                Daftar Kompetisi
            </h1>

            <p class="mx-auto mt-5 max-w-2xl text-slate-600">
                Temukan kompetisi yang sesuai dengan minat dan kemampuanmu.
                Tunjukkan kreativitas dan inovasimu!
            </p>
        </div>

        {{-- Daftar kompetisi --}}
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">

            @forelse ($competitions as $competition)

                <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                    <div class="flex h-48 items-center justify-center bg-[#06251D]">
                        @if ($competition->thumbnail)
                            <img
                                src="{{ asset('storage/' . $competition->thumbnail) }}"
                                alt="{{ $competition->name }}"
                                class="h-full w-full object-cover"
                            >
                        @else
                            <span class="text-5xl font-black text-emerald-400">
                                FT
                            </span>
                        @endif
                    </div>

                    <div class="p-6">

                        <div class="mb-4 flex items-center justify-between gap-3">

                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold uppercase text-emerald-800">
                                {{ $competition->type === 'team' ? 'Tim' : 'Individu' }}
                            </span>

                            <span class="text-xs font-semibold uppercase text-slate-500">
                                {{ str_replace('_', ' ', $competition->status) }}
                            </span>

                        </div>

                        <h2 class="text-xl font-bold text-[#06251D]">
                            {{ $competition->name }}
                        </h2>

                        <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-slate-600">
                            {{ $competition->short_description }}
                        </p>

                        <div class="mt-6 border-t border-slate-100 pt-4">

                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500">Kuota peserta</span>

                                <span class="font-bold text-[#06251D]">
                                    {{ $competition->quota ?? 'Tidak dibatasi' }}
                                </span>
                            </div>

                            <a
                                href="{{ route('kompetisi.show', $competition->slug) }}"
                                class="mt-5 inline-flex w-full items-center justify-center rounded-md bg-[#087B48] px-5 py-3 font-bold text-white transition hover:bg-[#0A9659]"
                            >
                                Lihat Detail
                            </a>

                        </div>

                    </div>

                </article>

            @empty

                <div class="col-span-full rounded-xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">

                    <h2 class="text-xl font-bold text-[#06251D]">
                        Belum Ada Kompetisi
                    </h2>

                    <p class="mt-2 text-slate-500">
                        Kompetisi akan segera diumumkan. Nantikan informasi selanjutnya!
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</section>

@endsection
