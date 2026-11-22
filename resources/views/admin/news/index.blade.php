@extends('layouts.admin')

@section('title', 'Manajemen Berita')

@section('content')
<div class="p-6 lg:p-8 space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-emerald-700">Konten Website</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900">Manajemen Berita</h1>
            <p class="mt-2 text-sm text-slate-500">
                Kelola informasi dan publikasi berita Festival Teknologi UNUSIA.
            </p>
        </div>

        <a href="{{ route('admin.news.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
            + Tambah Berita
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="font-bold text-slate-900">Daftar Berita</h2>
            <p class="mt-1 text-sm text-slate-500">
                Total {{ $news->total() }} berita terdaftar
            </p>
        </div>

        @if($news->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-2xl">📰</div>
                <h3 class="mt-4 font-semibold text-slate-900">Belum ada berita</h3>
                <p class="mt-2 text-sm text-slate-500">
                    Mulai buat berita pertama untuk website Festival Teknologi.
                </p>
                <a href="{{ route('admin.news.create') }}"
                   class="mt-5 inline-flex rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800">
                    Buat Berita
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Berita</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Penulis</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach($news as $item)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($item->thumbnail)
                                            <img
                                                src="{{ asset('storage/' . $item->thumbnail) }}"
                                                alt="{{ $item->title }}"
                                                class="h-14 w-20 rounded-lg object-cover"
                                            >
                                        @else
                                            <div class="flex h-14 w-20 items-center justify-center rounded-lg bg-slate-100 text-xl">
                                                📰
                                            </div>
                                        @endif

                                        <div class="max-w-xs">
                                            <a href="{{ route('admin.news.show', $item) }}"
                                               class="font-semibold text-slate-900 hover:text-emerald-700">
                                                {{ $item->title }}
                                            </a>
                                            <p class="mt-1 text-xs text-slate-500">
                                                /{{ $item->slug }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $item->category ?: 'Tanpa kategori' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $item->author?->name ?? 'Tidak diketahui' }}
                                </td>

                                <td class="px-6 py-4">
                                    @if($item->status === 'published')
                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            Published
                                        </span>
                                    @else
                                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Draft
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $item->created_at->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.news.show', $item) }}"
                                           class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100">
                                            Detail
                                        </a>

                                        <a href="{{ route('admin.news.edit', $item) }}"
                                           class="rounded-lg border border-blue-200 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-50">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.news.destroy', $item) }}"
                                              method="POST"
                                              data-confirm-delete>
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $news->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
