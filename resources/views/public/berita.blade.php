@extends('layouts.app')

@section('title', 'Berita - Festival Teknologi UNUSIA')

@section('content')
    <section class="bg-[#06251D] px-6 py-16 text-white">
        <div class="mx-auto max-w-7xl">
            <p class="mb-3 text-sm font-semibold uppercase tracking-[0.25em] text-emerald-400">
                Festival Technology
            </p>

            <h1 class="text-3xl font-extrabold md:text-5xl">
                Berita & Informasi
            </h1>

            <p class="mt-4 max-w-2xl leading-relaxed text-slate-300">
                Ikuti perkembangan terbaru, informasi kegiatan, dan berbagai cerita
                menarik dari Festival Teknologi UNUSIA.
            </p>
        </div>
    </section>

    <section class="bg-[#F4F7F5] px-6 py-14">
        <div class="mx-auto max-w-7xl">

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900">
                    Berita Terbaru
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Informasi terbaru dari Festival Teknologi UNUSIA
                </p>
            </div>

            @if ($news->isNotEmpty())
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($news as $item)
                        <a
                            href="{{ route('berita.show', $item->slug) }}"
                            class="group block cursor-pointer overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg"
                        >
                            @if ($item->thumbnail)
                                <img
                                    src="{{ asset('storage/' . $item->thumbnail) }}"
                                    alt="{{ $item->title }}"
                                    class="h-56 w-full object-cover transition duration-500 group-hover:scale-105"
                                >
                            @else
                                <div class="flex h-56 items-center justify-center bg-emerald-950 text-5xl text-emerald-400">
                                    📰
                                </div>
                            @endif

                            <div class="p-6">
                                @if ($item->category)
                                    <span class="inline-block rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        {{ $item->category }}
                                    </span>
                                @endif

                                <h3 class="mt-4 text-xl font-bold leading-snug text-slate-900 transition group-hover:text-emerald-700">
                                    {{ $item->title }}
                                </h3>

                                <p class="mt-3 text-sm leading-relaxed text-slate-600">
                                    {{ $item->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($item->content), 130) }}
                                </p>

                                <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                                    <p class="text-xs text-slate-500">
                                        {{ $item->author->name ?? 'Admin' }}
                                        <span class="mx-1">·</span>
                                        {{ $item->published_at->translatedFormat('d M Y') }}
                                    </p>

                                    <span class="text-sm font-semibold text-emerald-700 transition group-hover:translate-x-1">
                                        Baca →
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $news->links() }}
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-20 text-center">
                    <div class="text-5xl">📰</div>

                    <h3 class="mt-5 text-xl font-bold text-slate-800">
                        Belum Ada Berita
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Berita terbaru akan ditampilkan di halaman ini.
                    </p>
                </div>
            @endif

        </div>
    </section>
@endsection
