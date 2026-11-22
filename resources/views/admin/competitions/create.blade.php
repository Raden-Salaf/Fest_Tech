@extends('layouts.admin')

@section('title', 'Tambah Kompetisi')

@section('page-heading', 'Tambah Kompetisi')

@section('content')
<div class="mx-auto max-w-5xl space-y-6 p-4 sm:p-6 lg:p-8">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Tambah Kompetisi</h1>
            <p class="mt-1 text-sm text-slate-500">
                Masukkan informasi kompetisi yang akan ditampilkan di website.
            </p>
        </div>

        <a href="{{ route('admin.competitions.index') }}"
            class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.competitions.store') }}" method="POST"
        enctype="multipart/form-data"
        class="space-y-6">
        @csrf

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="text-lg font-bold text-slate-900">Informasi Kompetisi</h2>
            <p class="mt-1 text-sm text-slate-500">Informasi dasar yang akan diketahui peserta.</p>

            <div class="mt-6 grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                        Nama Kompetisi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        required maxlength="255"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        placeholder="Contoh: Web Development Competition">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="short_description" class="mb-2 block text-sm font-semibold text-slate-700">
                        Deskripsi Singkat
                    </label>
                    <textarea id="short_description" name="short_description" rows="2"
                        maxlength="500"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        placeholder="Ringkasan singkat kompetisi...">{{ old('short_description') }}</textarea>
                    @error('short_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">
                        Deskripsi Lengkap
                    </label>
                    <textarea id="description" name="description" rows="6"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        placeholder="Jelaskan ketentuan, mekanisme, dan informasi kompetisi...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="mb-2 block text-sm font-semibold text-slate-700">
                        Jenis Kompetisi <span class="text-red-500">*</span>
                    </label>
                    <select id="type" name="type" required
                        class="w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                        <option value="">Pilih jenis</option>
                        <option value="individual" @selected(old('type') === 'individual')>Individu</option>
                        <option value="team" @selected(old('type', 'team') === 'team')>Tim</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">
                        Status Kompetisi <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required
                        class="w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                        <option value="upcoming" @selected(old('status', 'upcoming') === 'upcoming')>Segera Dibuka</option>
                        <option value="open" @selected(old('status') === 'open')>Pendaftaran Dibuka</option>
                        <option value="ongoing" @selected(old('status') === 'ongoing')>Sedang Berlangsung</option>
                        <option value="finished" @selected(old('status') === 'finished')>Selesai</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="text-lg font-bold text-slate-900">Jadwal Kompetisi</h2>
            <p class="mt-1 text-sm text-slate-500">Atur periode pendaftaran dan pelaksanaan.</p>

            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                @foreach ([
                    'registration_start' => 'Pendaftaran Dimulai',
                    'registration_end' => 'Pendaftaran Ditutup',
                    'competition_start' => 'Kompetisi Dimulai',
                    'competition_end' => 'Kompetisi Berakhir',
                ] as $field => $label)
                    <div>
                        <label for="{{ $field }}" class="mb-2 block text-sm font-semibold text-slate-700">
                            {{ $label }}
                        </label>
                        <input type="datetime-local" id="{{ $field }}" name="{{ $field }}"
                            value="{{ old($field) }}"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                        @error($field)
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="text-lg font-bold text-slate-900">Pengaturan Peserta</h2>
            <p class="mt-1 text-sm text-slate-500">Atur jumlah anggota dan kuota peserta.</p>

            <div class="mt-6 grid gap-5 sm:grid-cols-3">
                <div>
                    <label for="min_team_members" class="mb-2 block text-sm font-semibold text-slate-700">
                        Minimal Anggota Tim
                    </label>
                    <input type="number" id="min_team_members" name="min_team_members"
                        min="1" value="{{ old('min_team_members') }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        placeholder="Contoh: 2">
                    @error('min_team_members')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="max_team_members" class="mb-2 block text-sm font-semibold text-slate-700">
                        Maksimal Anggota Tim
                    </label>
                    <input type="number" id="max_team_members" name="max_team_members"
                        min="1" value="{{ old('max_team_members') }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        placeholder="Contoh: 3">
                    @error('max_team_members')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quota" class="mb-2 block text-sm font-semibold text-slate-700">
                        Kuota Peserta
                    </label>
                    <input type="number" id="quota" name="quota" min="1"
                        value="{{ old('quota') }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        placeholder="Contoh: 30">
                    @error('quota')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="text-lg font-bold text-slate-900">Thumbnail Kompetisi</h2>
            <p class="mt-1 text-sm text-slate-500">Gunakan gambar JPG, PNG, atau WebP maksimal 2 MB.</p>

            <div class="mt-5">
                <input type="file" id="thumbnail" name="thumbnail" accept="image/jpeg,image/png,image/webp"
                    class="block w-full cursor-pointer rounded-xl border border-slate-300 text-sm text-slate-600 file:mr-4 file:cursor-pointer file:border-0 file:bg-emerald-50 file:px-4 file:py-3 file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100">
                @error('thumbnail')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-bold text-slate-900">Publikasi Kompetisi</h2>
                    <p class="mt-1 text-sm text-slate-500">Tentukan apakah kompetisi ditampilkan kepada publik.</p>
                </div>

                <label class="inline-flex cursor-pointer items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1"
                        @checked(old('is_active', '1') == '1')
                        class="h-5 w-5 cursor-pointer rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <span class="text-sm font-semibold text-slate-700">Aktif</span>
                </label>
            </div>
            @error('is_active')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </section>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.competitions.index') }}"
                class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                Batal
            </a>
            <button type="submit"
                class="inline-flex cursor-pointer items-center justify-center rounded-xl bg-emerald-700 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800">
                Simpan Kompetisi
            </button>
        </div>
    </form>
</div>
@endsection
