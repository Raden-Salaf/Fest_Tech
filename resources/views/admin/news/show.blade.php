@extends('layouts.admin')

@section('title', 'Detail Berita')

@section('content')
<div class="p-6 lg:p-8 space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('admin.news.index') }}"
               class="text-sm font-medium text-emerald-700 hover:text-emerald-900">
                ← Kembali ke daftar berita
            </a>

            <h1 class="mt-4 text-2xl font-bold text-slate-900">Detail Berita</h1>
            <p class="mt-2 text-sm text-slate-500">
                Informasi lengkap berita Festival Teknologi UNUSIA.
            </p>
        </div>

        <a href="{{ route('admin.news.edit', $news) }}"
           class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800">
            Edit Berita
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        <div class="space-y-6 xl:col-span-2">

            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                @if($news->thumbnail)
                    <img
                        src="{{ asset('storage/' . $news->thumbnail) }}"
                        alt="{{ $news->title }}"
                        class="max-h-[420px] w-full object-cover"
                    >
                @else
                    <div class="flex h-56 items-center justify-center bg-slate-100 text-5xl">
                        📰
                    </div>
                @endif

                <div class="p-6 lg:p-8">

                    <div class="flex flex-wrap items-center gap-2">
                        @if($news->category)
                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                {{ $news->category }}
                            </span>
                        @endif

                        @if($news->status === 'published')
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                Published
                            </span>
                        @else
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                Draft
                            </span>
                        @endif
                    </div>

                    <h2 class="mt-5 text-2xl font-bold leading-tight text-slate-900 lg:text-3xl">
                        {{ $news->title }}
                    </h2>

                    <p class="mt-3 text-sm text-slate-500">
                        Oleh {{ $news->author?->name ?? 'Tidak diketahui' }}
                        <span class="mx-2">·</span>
                        {{ $news->created_at->format('d F Y, H:i') }}
                    </p>

                    @if($news->excerpt)
                        <div class="mt-6 rounded-xl border-l-4 border-emerald-600 bg-emerald-50 p-5">
                            <p class="text-sm font-medium leading-7 text-emerald-950">
                                {{ $news->excerpt }}
                            </p>
                        </div>
                    @endif

                    <div class="mt-8 whitespace-pre-line break-words text-sm leading-8 text-slate-700">{{ $news->content }}</div>
                </div>
            </article>
        </div>

        <aside class="space-y-6">

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="font-bold text-slate-900">Informasi Publikasi</h3>

                <dl class="mt-5 space-y-4 text-sm">
                    <div>
                        <dt class="text-slate-500">Status</dt>
                        <dd class="mt-1 font-semibold text-slate-900">
                            {{ ucfirst($news->status) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Tanggal Publikasi</dt>
                        <dd class="mt-1 font-semibold text-slate-900">
                            {{ $news->published_at?->format('d F Y, H:i') ?? 'Belum diterbitkan' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Penulis</dt>
                        <dd class="mt-1 font-semibold text-slate-900">
                            {{ $news->author?->name ?? 'Tidak diketahui' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Kategori</dt>
                        <dd class="mt-1 font-semibold text-slate-900">
                            {{ $news->category ?: 'Tanpa kategori' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Slug</dt>
                        <dd class="mt-1 break-all font-medium text-slate-700">
                            {{ $news->slug }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Terakhir diperbarui</dt>
                        <dd class="mt-1 font-medium text-slate-700">
                            {{ $news->updated_at->format('d F Y, H:i') }}
                        </dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-2xl border border-red-200 bg-white p-6 shadow-sm">
                <h3 class="font-bold text-slate-900">Hapus Berita</h3>
                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Berita akan dipindahkan ke status soft delete dan tidak tampil dalam daftar aktif.
                </p>

                <form action="{{ route('admin.news.destroy', $news) }}"
                      method="POST"
                      class="mt-4"
                      data-confirm-delete>
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="w-full rounded-xl border border-red-200 px-4 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                        Hapus Berita
                    </button>
                </form>
            </section>

        </aside>
    </div>
</div>
@endsection
