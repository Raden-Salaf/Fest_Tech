@extends('layouts.admin')

@section('title', 'Detail Kompetisi')
@section('page-heading', 'Detail Kompetisi')

@section('content')
<div class="mx-auto max-w-6xl space-y-6 p-4 sm:p-6 lg:p-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                {{ $competition->name }}
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Detail informasi dan konfigurasi kompetisi.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.competitions.index') }}"
               class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                Kembali
            </a>

            <a href="{{ route('admin.competitions.edit', $competition) }}"
               class="inline-flex cursor-pointer items-center justify-center rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">
                Edit Kompetisi
            </a>
        </div>
    </div>

    @php
        $statusLabels = [
            'upcoming' => 'Segera Dibuka',
            'open' => 'Pendaftaran Dibuka',
            'ongoing' => 'Sedang Berlangsung',
            'finished' => 'Selesai',
        ];

        $typeLabels = [
            'individual' => 'Individu',
            'team' => 'Tim',
        ];

        $dateFields = [
            'registration_start' => 'Pendaftaran Dimulai',
            'registration_end' => 'Pendaftaran Ditutup',
            'competition_start' => 'Kompetisi Dimulai',
            'competition_end' => 'Kompetisi Berakhir',
        ];
    @endphp

    {{-- Statistik --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Jenis Kompetisi</p>
            <p class="mt-2 text-lg font-bold text-slate-900">
                {{ $typeLabels[$competition->type] ?? ucfirst($competition->type) }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Status</p>
            <p class="mt-2 text-lg font-bold text-slate-900">
                {{ $statusLabels[$competition->status] ?? ucfirst($competition->status) }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Pendaftaran</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">
                {{ $competition->registrations_count ?? 0 }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Publikasi</p>
            <p class="mt-2 text-lg font-bold {{ $competition->is_active ? 'text-emerald-700' : 'text-red-600' }}">
                {{ $competition->is_active ? 'Aktif' : 'Nonaktif' }}
            </p>
        </div>
    </div>

    {{-- Informasi Umum --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        @if ($competition->thumbnail)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($competition->thumbnail) }}"
                 alt="Thumbnail {{ $competition->name }}"
                 class="h-56 w-full object-cover sm:h-72">
        @endif

        <div class="p-5 sm:p-7">
            <h2 class="text-lg font-bold text-slate-900">
                Informasi Umum
            </h2>

            <div class="mt-5 grid gap-5 md:grid-cols-2">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Nama Kompetisi
                    </p>
                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $competition->name }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Slug
                    </p>
                    <p class="mt-1 break-all font-medium text-slate-700">
                        {{ $competition->slug }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Deskripsi Singkat
                    </p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-700">
                        {{ $competition->short_description ?: 'Belum ada deskripsi singkat.' }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Deskripsi Lengkap
                    </p>
                    <div class="mt-1 whitespace-pre-line text-sm leading-relaxed text-slate-700">
                        {{ $competition->description ?: 'Belum ada deskripsi lengkap.' }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Jadwal --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <h2 class="text-lg font-bold text-slate-900">
            Jadwal Kompetisi
        </h2>

        <div class="mt-5 grid gap-5 sm:grid-cols-2">
            @foreach ($dateFields as $field => $label)
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        {{ $label }}
                    </p>
                    <p class="mt-2 font-semibold text-slate-800">
                        {{ $competition->{$field}?->translatedFormat('d F Y, H:i') ?? 'Belum ditentukan' }}
                    </p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Pengaturan Peserta --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <h2 class="text-lg font-bold text-slate-900">
            Pengaturan Peserta
        </h2>

        <div class="mt-5 grid gap-4 sm:grid-cols-3">
            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Minimal Anggota
                </p>
                <p class="mt-2 text-xl font-bold text-slate-900">
                    {{ $competition->min_team_members ?? '—' }}
                </p>
            </div>

            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Maksimal Anggota
                </p>
                <p class="mt-2 text-xl font-bold text-slate-900">
                    {{ $competition->max_team_members ?? '—' }}
                </p>
            </div>

            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Kuota Peserta
                </p>
                <p class="mt-2 text-xl font-bold text-slate-900">
                    {{ $competition->quota ?? 'Tidak dibatasi' }}
                </p>
            </div>
        </div>
    </section>

    {{-- Formulir Pendaftaran --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">
                    Formulir Pendaftaran
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Kelola pertanyaan yang harus diisi peserta kompetisi ini.
                </p>
            </div>

            <a href="{{ route('admin.competitions.fields.index', $competition) }}"
               class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800">
                <span aria-hidden="true">⚙</span>
                Kelola Field Formulir
            </a>
        </div>

        @if ($competition->formFields->isNotEmpty())
            <div class="mt-5 overflow-x-auto">
                <table class="w-full min-w-[600px] text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Label</th>
                            <th class="px-4 py-3">Nama Field</th>
                            <th class="px-4 py-3">Tipe</th>
                            <th class="px-4 py-3">Wajib</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach ($competition->formFields as $field)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">
                                    {{ $field->label }}
                                </td>

                                <td class="px-4 py-3 text-slate-600">
                                    {{ $field->name }}
                                </td>

                                <td class="px-4 py-3 text-slate-600">
                                    {{ strtoupper($field->type) }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $field->is_required ? 'Ya' : 'Tidak' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $field->is_active ? 'Aktif' : 'Nonaktif' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="mt-5 rounded-xl bg-slate-50 p-5">
                <p class="font-semibold text-slate-700">
                    Belum ada field tambahan.
                </p>
                <p class="mt-1 text-sm text-slate-500">
                    Tambahkan pertanyaan khusus untuk peserta kompetisi ini melalui tombol Kelola Field Formulir.
                </p>
            </div>
        @endif
    </section>

    {{-- Pengaturan Pembayaran --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <h2 class="text-lg font-bold text-slate-900">
            Pengaturan Pembayaran
        </h2>

        <div class="mt-4 rounded-xl bg-slate-50 p-4">
            @if ($competition->paymentSetting)
                <p class="font-semibold text-emerald-700">
                    Pengaturan pembayaran tersedia
                </p>
                <p class="mt-1 text-sm text-slate-500">
                    Konfigurasi pembayaran untuk kompetisi ini sudah dibuat.
                </p>
            @else
                <p class="font-semibold text-slate-700">
                    Belum ada pengaturan pembayaran
                </p>
                <p class="mt-1 text-sm text-slate-500">
                    Pengaturan pembayaran belum dikonfigurasi untuk kompetisi ini.
                </p>
            @endif
        </div>
    </section>

    {{-- Aksi --}}
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">
        <form action="{{ route('admin.competitions.destroy', $competition) }}"
              method="POST"
              data-confirm-delete>
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="inline-flex w-full cursor-pointer items-center justify-center rounded-xl border border-red-200 px-5 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50 sm:w-auto">
                Hapus Kompetisi
            </button>
        </form>

        <a href="{{ route('admin.competitions.edit', $competition) }}"
           class="inline-flex cursor-pointer items-center justify-center rounded-xl bg-emerald-700 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800">
            Edit Kompetisi
        </a>
    </div>

</div>
@endsection
