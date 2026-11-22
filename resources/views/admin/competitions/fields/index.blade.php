@extends('layouts.admin')

@section('title', 'Formulir Kompetisi - ' . $competition->name)
@section('page-heading', 'Manajemen Kompetisi')

@section('content')
<div class="min-h-screen bg-slate-50 px-5 py-7 sm:px-8 lg:px-10">
    <div class="mx-auto max-w-7xl space-y-7">

        {{-- Breadcrumb --}}
        <div class="flex flex-wrap items-center gap-2 text-sm">
            <a href="{{ route('admin.competitions.index') }}"
               class="font-medium text-slate-500 transition hover:text-emerald-700">
                Kompetisi
            </a>

            <span class="text-slate-300">/</span>

            <a href="{{ route('admin.competitions.show', $competition) }}"
               class="font-medium text-slate-500 transition hover:text-emerald-700">
                Detail Kompetisi
            </a>

            <span class="text-slate-300">/</span>

            <span class="font-semibold text-emerald-700">
                Formulir Pendaftaran
            </span>
        </div>

        {{-- Page Header --}}
        <div class="flex flex-col justify-between gap-5 md:flex-row md:items-end">
            <div class="max-w-2xl">
                <div class="mb-3 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-emerald-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Pengaturan Kompetisi
                </div>

                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">
                    Formulir Pendaftaran
                </h1>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Kelola pertanyaan dan informasi yang harus dilengkapi peserta
                    ketika mendaftar kompetisi.
                </p>

                <div class="mt-4 flex items-center gap-2 text-sm">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                        🏆
                    </span>

                    <span class="font-semibold text-slate-800">
                        {{ $competition->name }}
                    </span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.competitions.show', $competition) }}"
                   class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
                    <span aria-hidden="true">←</span>
                    Kembali
                </a>

                <a href="{{ route('admin.competitions.fields.create', $competition) }}"
                   class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-bold text-white shadow-sm shadow-emerald-900/10 transition hover:bg-emerald-800 hover:shadow-md">
                    <span class="text-lg leading-none">+</span>
                    Tambah Field
                </a>
            </div>
        </div>

        {{-- Information Banner --}}
        <div class="flex gap-4 rounded-2xl border border-emerald-100 bg-emerald-50/70 p-5">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-lg">
                📝
            </div>

            <div>
                <h2 class="text-sm font-bold text-emerald-900">
                    Formulir dinamis
                </h2>

                <p class="mt-1 max-w-3xl text-sm leading-6 text-emerald-800/80">
                    Field yang berstatus aktif akan muncul pada halaman pendaftaran peserta.
                    Kamu bisa menentukan jenis input, pilihan jawaban, kewajiban pengisian,
                    serta urutan setiap pertanyaan.
                </p>
            </div>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">
                        Total Field
                    </p>

                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-lg">
                        📋
                    </span>
                </div>

                <div class="mt-3 flex items-end gap-2">
                    <span class="text-3xl font-extrabold tracking-tight text-slate-900">
                        {{ $fields->count() }}
                    </span>
                    <span class="mb-1 text-sm text-slate-400">
                        pertanyaan
                    </span>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">
                        Field Aktif
                    </p>

                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-lg">
                        ✓
                    </span>
                </div>

                <div class="mt-3 flex items-end gap-2">
                    <span class="text-3xl font-extrabold tracking-tight text-emerald-700">
                        {{ $fields->where('is_active', true)->count() }}
                    </span>
                    <span class="mb-1 text-sm text-slate-400">
                        ditampilkan
                    </span>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">
                        Wajib Diisi
                    </p>

                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-lg">
                        *
                    </span>
                </div>

                <div class="mt-3 flex items-end gap-2">
                    <span class="text-3xl font-extrabold tracking-tight text-amber-600">
                        {{ $fields->where('is_required', true)->where('is_active', true)->count() }}
                    </span>
                    <span class="mb-1 text-sm text-slate-400">
                        field wajib
                    </span>
                </div>
            </div>
        </div>

        {{-- Field Table --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">

            <div class="flex flex-col justify-between gap-3 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-base font-bold text-slate-900">
                        Daftar Pertanyaan
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Atur informasi yang dibutuhkan dari peserta kompetisi.
                    </p>
                </div>

                <span class="inline-flex w-fit items-center gap-2 rounded-lg bg-slate-100 px-3 py-2 text-xs font-bold text-slate-600">
                    {{ $fields->count() }} Field Tersimpan
                </span>
            </div>

            @if ($fields->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[850px] text-left">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/80">
                                <th class="w-20 px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">
                                    No.
                                </th>

                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">
                                    Informasi Field
                                </th>

                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">
                                    Jenis Input
                                </th>

                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">
                                    Ketentuan
                                </th>

                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-400">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach ($fields as $field)
                                <tr class="transition hover:bg-slate-50/70">

                                    <td class="px-6 py-5">
                                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-sm font-bold text-slate-600">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-5">
                                        <p class="font-bold text-slate-800">
                                            {{ $field->label }}
                                        </p>

                                        <p class="mt-1 font-mono text-xs text-slate-400">
                                            {{ $field->name }}
                                        </p>

                                        @if (in_array($field->type, ['select', 'radio', 'checkbox']))
                                            <p class="mt-2 text-xs text-slate-500">
                                                {{ count($field->options ?? []) }} pilihan tersedia
                                            </p>
                                        @endif
                                    </td>

                                    <td class="px-6 py-5">
                                        <span class="inline-flex rounded-lg bg-blue-50 px-2.5 py-1.5 text-xs font-bold text-blue-700">
                                            {{ strtoupper($field->type) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-5">
                                        @if ($field->is_required)
                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-600">
                                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                                Wajib diisi
                                            </span>
                                        @else
                                            <span class="text-xs font-medium text-slate-400">
                                                Opsional
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-5">
                                        @if ($field->is_active)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-500">
                                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-5">
                                        <div class="flex items-center justify-end gap-2">

                                            <a href="{{ route('admin.competitions.fields.edit', [$competition, $field]) }}"
                                               class="inline-flex cursor-pointer items-center justify-center rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.competitions.fields.destroy', [$competition, $field]) }}"
                                                  method="POST"
                                                  data-confirm-delete
                                                  data-confirm-title="Hapus field?"
                                                  data-confirm-text="Field {{ $field->label }} akan dihapus dari formulir kompetisi ini."
                                                  data-confirm-button="Ya, hapus">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="inline-flex cursor-pointer items-center justify-center rounded-lg border border-rose-100 bg-white px-3.5 py-2 text-xs font-bold text-rose-600 transition hover:border-rose-200 hover:bg-rose-50">
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

                <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4">
                    <p class="text-xs text-slate-400">
                        Urutan pertanyaan pada formulir peserta mengikuti nilai urutan field yang sudah diatur.
                    </p>
                </div>

            @else
                <div class="px-6 py-16 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-50 text-3xl">
                        📝
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-slate-900">
                        Belum Ada Pertanyaan
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        Formulir kompetisi ini belum memiliki field tambahan.
                        Tambahkan pertanyaan untuk mengumpulkan informasi
                        yang dibutuhkan dari peserta.
                    </p>

                    <a href="{{ route('admin.competitions.fields.create', $competition) }}"
                       class="mt-6 inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-800">
                        + Tambah Pertanyaan Pertama
                    </a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
