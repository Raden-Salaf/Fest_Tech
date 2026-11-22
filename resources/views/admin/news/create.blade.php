@extends('layouts.admin')

@section('title', 'Tambah Berita')

@section('content')
<div class="p-6 lg:p-8 space-y-6">

    <div>
        <a href="{{ route('admin.news.index') }}"
           class="text-sm font-medium text-emerald-700 hover:text-emerald-900">
            ← Kembali ke daftar berita
        </a>

        <h1 class="mt-4 text-2xl font-bold text-slate-900">Tambah Berita</h1>
        <p class="mt-2 text-sm text-slate-500">
            Buat konten berita baru untuk Festival Teknologi UNUSIA.
        </p>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold">Ada kesalahan pada input:</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.news.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6">

        @csrf

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

            <div class="space-y-6 xl:col-span-2">

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
                    <div>
                        <h2 class="font-bold text-slate-900">Informasi Berita</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Masukkan informasi utama konten berita.
                        </p>
                    </div>

                    <div>
                        <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">
                            Judul Berita <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            value="{{ old('title') }}"
                            required
                            maxlength="255"
                            placeholder="Masukkan judul berita"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        >
                        @error('title')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="excerpt" class="mb-2 block text-sm font-semibold text-slate-700">
                            Ringkasan Berita
                        </label>
                        <textarea
                            name="excerpt"
                            id="excerpt"
                            rows="3"
                            maxlength="1000"
                            placeholder="Tuliskan ringkasan singkat berita..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        >{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="content" class="mb-2 block text-sm font-semibold text-slate-700">
                            Isi Berita <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            name="content"
                            id="content"
                            rows="14"
                            required
                            placeholder="Tuliskan isi berita secara lengkap..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm leading-7 outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        >{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-slate-400">
                            Isi berita saat ini menggunakan teks biasa.
                        </p>
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    <div>
                        <h2 class="font-bold text-slate-900">Thumbnail Berita</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Gambar sampul yang ditampilkan pada halaman berita.
                        </p>
                    </div>

                    <input
                        type="file"
                        name="thumbnail"
                        id="thumbnail"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="block w-full rounded-xl border border-slate-300 p-3 text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100"
                    >

                    <p class="text-xs text-slate-400">
                        Format JPG, JPEG, PNG, atau WebP. Maksimal 2 MB.
                    </p>

                    <div id="thumbnail-preview-wrapper" class="hidden">
                        <img id="thumbnail-preview" src="" alt="Preview thumbnail"
                             class="max-h-64 rounded-xl object-cover">
                    </div>

                    @error('thumbnail')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </section>
            </div>

            <div class="space-y-6">

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
                    <div>
                        <h2 class="font-bold text-slate-900">Pengaturan Publikasi</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Atur status dan waktu publikasi.
                        </p>
                    </div>

                    <div>
                        <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">
                            Status Berita <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="status"
                            id="status"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        >
                            <option value="draft" @selected(old('status', 'draft') === 'draft')>
                                Draft
                            </option>
                            <option value="published" @selected(old('status') === 'published')>
                                Published
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="published-at-wrapper">
                        <label for="published_at" class="mb-2 block text-sm font-semibold text-slate-700">
                            Jadwal Publikasi
                        </label>
                        <input
                            type="datetime-local"
                            name="published_at"
                            id="published_at"
                            value="{{ old('published_at') }}"
                            class="w-full rounded-xl border border-slate-300 px-3 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        >
                        <p class="mt-2 text-xs text-slate-400">
                            Kosongkan untuk menerbitkan sekarang.
                        </p>
                        @error('published_at')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    <div>
                        <h2 class="font-bold text-slate-900">Kategori</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Kelompokkan berita berdasarkan topik.
                        </p>
                    </div>

                    <div>
                        <label for="category" class="mb-2 block text-sm font-semibold text-slate-700">
                            Kategori Berita
                        </label>
                        <input
                            type="text"
                            name="category"
                            id="category"
                            value="{{ old('category') }}"
                            maxlength="100"
                            placeholder="Contoh: Teknologi"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        >
                        @error('category')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <div class="flex flex-col gap-3">
                    <button type="submit"
                            class="w-full rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800">
                        Simpan Berita
                    </button>

                    <a href="{{ route('admin.news.index') }}"
                       class="w-full rounded-xl border border-slate-300 px-5 py-3 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const statusInput = document.getElementById('status');
    const publishedAtWrapper = document.getElementById('published-at-wrapper');
    const thumbnailInput = document.getElementById('thumbnail');
    const thumbnailPreview = document.getElementById('thumbnail-preview');
    const thumbnailPreviewWrapper = document.getElementById('thumbnail-preview-wrapper');

    function togglePublishedAt() {
        publishedAtWrapper.classList.toggle('hidden', statusInput.value !== 'published');
    }

    statusInput.addEventListener('change', togglePublishedAt);
    togglePublishedAt();

    thumbnailInput.addEventListener('change', function () {
        const file = this.files[0];

        if (file) {
            thumbnailPreview.src = URL.createObjectURL(file);
            thumbnailPreviewWrapper.classList.remove('hidden');
        } else {
            thumbnailPreviewWrapper.classList.add('hidden');
        }
    });
</script>
@endsection
