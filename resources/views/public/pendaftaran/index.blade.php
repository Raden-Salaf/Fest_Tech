@extends('layouts.app')

@section('title', 'Pendaftaran Saya - Festival Teknologi UNUSIA')

@section('content')
<div class="min-h-screen bg-[#F4F7F5] px-5 py-12 md:px-10">
    <div class="mx-auto max-w-6xl">

        {{-- Header halaman --}}
        <div class="mb-10">
            <span class="text-sm font-bold uppercase tracking-[0.2em] text-emerald-700">
                Participant Area
            </span>

            <h1 class="mt-3 text-3xl font-extrabold text-[#06251D] md:text-4xl">
                Pendaftaran Saya
            </h1>

            <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 md:text-base">
                Pantau seluruh kompetisi yang telah kamu ikuti,
                periksa status verifikasi, dan lihat riwayat pendaftaranmu.
            </p>
        </div>

        {{-- Ringkasan --}}
        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Total Pendaftaran
                </p>

                <p class="mt-2 text-3xl font-extrabold text-[#06251D]">
                    {{ $registrations->total() }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Halaman Saat Ini
                </p>

                <p class="mt-2 text-3xl font-extrabold text-emerald-700">
                    {{ $registrations->currentPage() }}
                </p>
            </div>
        </div>

        {{-- Daftar pendaftaran --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="text-lg font-bold text-[#06251D]">
                    Riwayat Kompetisi
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Pilih salah satu pendaftaran untuk melihat detailnya.
                </p>
            </div>

            @forelse ($registrations as $registration)

                @php
                    $verificationStatus = $registration->verification_status;

                    $statusConfig = match ($verificationStatus) {
                        'approved' => [
                            'label' => 'Disetujui',
                            'class' => 'bg-emerald-100 text-emerald-800',
                            'dot' => 'bg-emerald-500',
                        ],
                        'rejected' => [
                            'label' => 'Ditolak',
                            'class' => 'bg-red-100 text-red-800',
                            'dot' => 'bg-red-500',
                        ],
                        default => [
                            'label' => 'Menunggu Verifikasi',
                            'class' => 'bg-amber-100 text-amber-800',
                            'dot' => 'bg-amber-500',
                        ],
                    };
                @endphp

                <div class="border-b border-slate-100 p-6 last:border-b-0">

                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                        {{-- Informasi kompetisi --}}
                        <div class="min-w-0 flex-1">

                            <div class="mb-3 flex flex-wrap items-center gap-2">
                                <span class="rounded-md bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                    {{ $registration->registration_code ?? 'Kode belum tersedia' }}
                                </span>

                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold {{ $statusConfig['class'] }}">
                                    <span class="h-2 w-2 rounded-full {{ $statusConfig['dot'] }}"></span>
                                    {{ $statusConfig['label'] }}
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-[#06251D]">
                                {{ $registration->competition->name ?? 'Kompetisi tidak tersedia' }}
                            </h3>

                            <div class="mt-3 flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-500">

                                @if ($registration->team_name)
                                    <span>
                                        <span class="font-semibold text-slate-700">Tim:</span>
                                        {{ $registration->team_name }}
                                    </span>
                                @else
                                    <span>
                                        <span class="font-semibold text-slate-700">Jenis:</span>
                                        Individual
                                    </span>
                                @endif

                                <span>
                                    <span class="font-semibold text-slate-700">Anggota:</span>
                                    {{ $registration->members->count() }} orang
                                </span>

                                <span>
                                    <span class="font-semibold text-slate-700">Tanggal:</span>
                                    {{ $registration->registered_at
                                        ? $registration->registered_at->translatedFormat('d F Y, H:i')
                                        : $registration->created_at->translatedFormat('d F Y, H:i') }}
                                </span>

                            </div>
                        </div>

                        {{-- Tombol detail --}}
                        <div class="shrink-0">
                            <a
                                href="{{ route('pendaftaran.status', $registration) }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#087B48] px-5 py-3 text-sm font-bold text-white transition duration-200 hover:bg-[#06643B] hover:shadow-lg hover:shadow-emerald-900/10 cursor-pointer sm:w-auto"
                            >
                                Lihat Status
                                <span aria-hidden="true">→</span>
                            </a>
                        </div>

                    </div>
                </div>

            @empty

                {{-- Kondisi belum pernah mendaftar --}}
                <div class="px-6 py-16 text-center">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-50 text-3xl">
                        🏆
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-[#06251D]">
                        Belum Ada Pendaftaran
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        Kamu belum mendaftarkan diri ke kompetisi mana pun.
                        Yuk, temukan kompetisi yang menarik dan mulai perjalananmu!
                    </p>

                    <a
                        href="{{ route('kompetisi.index') }}"
                        class="mt-6 inline-flex items-center justify-center rounded-xl bg-[#087B48] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#06643B] cursor-pointer"
                    >
                        Jelajahi Kompetisi
                        <span class="ml-2" aria-hidden="true">→</span>
                    </a>

                </div>

            @endforelse

        </div>

        {{-- Pagination --}}
        @if ($registrations->hasPages())
            <div class="mt-6">
                {{ $registrations->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
