@extends('layouts.app')

@section('title', $news->title . ' - Festival Teknologi UNUSIA')

@section('content')
    <section class="bg-[#06251D] px-6 py-12 text-white">
        <div class="mx-auto max-w-5xl">
            <a
                href="{{ route('berita.index') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-emerald-400 transition hover:text-emerald-300"
            >
                <span aria-hidden="true">←</span>
                Kembali ke Berita
            </a>

            @if ($news->category)
                <div class="mt-8">
                    <span class="inline-flex rounded-full bg-emerald-500/15 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-emerald-300">
                        {{ $news->category }}
                    </span>
                </div>
            @endif

            <h1 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight md:text-5xl">
                {{ $news->title }}
            </h1>

            @if ($news->excerpt)
                <p class="mt-5 max-w-3xl text-base leading-relaxed text-slate-300 md:text-lg">
                    {{ $news->excerpt }}
                </p>
            @endif

            <div class="mt-8 flex flex-wrap items-center gap-x-5 gap-y-2 border-t border-white/10 pt-6 text-sm text-slate-400">
                <span>
                    Oleh
                    <strong class="font-semibold text-white">
                        {{ $news->author->name ?? 'Admin' }}
                    </strong>
                </span>

                <span class="hidden text-slate-600 sm:inline">•</span>

                <time datetime="{{ $news->published_at->toIso8601String() }}">
                    {{ $news->published_at->translatedFormat('d F Y') }}
                </time>
            </div>
        </div>
    </section>

    <section class="bg-[#F4F7F5] px-6 py-12">
        <article class="mx-auto max-w-5xl">
            @if ($news->thumbnail)
                <div class="mb-10 overflow-hidden rounded-2xl bg-slate-200 shadow-lg">
                    <img
                        src="{{ asset('storage/' . $news->thumbnail) }}"
                        alt="{{ $news->title }}"
                        class="max-h-[560px] w-full object-cover"
                    >
                </div>
            @endif

            <div class="mx-auto max-w-3xl">
                <div class="text-base leading-8 text-slate-700 md:text-lg">
                    {!! nl2br(e($news->content)) !!}
                </div>

                <div class="mt-12 border-t border-slate-200 pt-8">
                    <a
                        href="{{ route('berita.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#06251D] px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800"
                    >
                        <span aria-hidden="true">←</span>
                        Lihat Berita Lainnya
                    </a>
                </div>
            </div>
        </article>
    </section>
@endsection
