@extends('layouts.admin')

@section('title', 'Pengaturan Pembayaran - FEST.TECH')

@section('content')
    <main class="p-4 sm:p-6 lg:p-8">

        {{-- Header halaman --}}
        <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="text-sm font-semibold text-emerald-700">
                    Administrator / Pembayaran
                </p>

                <h1 class="mt-2 text-2xl font-extrabold text-slate-800 sm:text-3xl">
                    Pengaturan Pembayaran
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Kelola biaya pendaftaran dan metode pembayaran setiap kompetisi.
                </p>
            </div>
        </div>

        {{-- Ringkasan jumlah kompetisi --}}
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Total Kompetisi
                </p>

                <p class="mt-2 text-3xl font-extrabold text-slate-800">
                    {{ $competitions->total() }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Halaman Saat Ini
                </p>

                <p class="mt-2 text-3xl font-extrabold text-emerald-700">
                    {{ $competitions->currentPage() }}
                </p>
            </div>
        </div>

        {{-- Tabel daftar kompetisi --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-5 sm:px-6">
                <h2 class="font-bold text-slate-800">
                    Daftar Kompetisi
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Pilih kompetisi untuk mengatur biaya dan metode pembayarannya.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-left text-sm">

                    {{-- Kepala tabel --}}
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-4 font-bold">Kompetisi</th>
                            <th class="px-6 py-4 font-bold">Jenis</th>
                            <th class="px-6 py-4 font-bold">Biaya</th>
                            <th class="px-6 py-4 font-bold">Status Pembayaran</th>
                            <th class="px-6 py-4 text-right font-bold">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Isi tabel --}}
                    <tbody class="divide-y divide-slate-100">

                        @forelse ($competitions as $competition)

                            @php
                                // Ambil konfigurasi pembayaran jika sudah tersedia.
                                $payment = $competition->paymentSetting;

                                // Tentukan apakah kompetisi menggunakan pembayaran.
                                $isPaid = $payment && $payment->is_payment_required;
                            @endphp

                            <tr class="transition hover:bg-slate-50">

                                {{-- Nama kompetisi --}}
                                <td class="px-6 py-5">
                                    <p class="font-bold text-slate-800">
                                        {{ $competition->name }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $competition->slug }}
                                    </p>
                                </td>

                                {{-- Jenis kompetisi --}}
                                <td class="px-6 py-5">
                                    <span class="rounded-lg bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                        {{ ucfirst($competition->type) }}
                                    </span>
                                </td>

                                {{-- Biaya pendaftaran --}}
                                <td class="px-6 py-5">
                                    @if ($isPaid)
                                        <span class="font-bold text-slate-800">
                                            Rp{{ number_format($payment->fee_amount, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="font-semibold text-emerald-700">
                                            Gratis
                                        </span>
                                    @endif
                                </td>

                                {{-- Status pembayaran --}}
                                <td class="px-6 py-5">
                                    @if (!$payment)
                                        <span class="rounded-full bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700">
                                            Belum Diatur
                                        </span>
                                    @elseif ($isPaid)
                                        <span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                                            Berbayar
                                        </span>
                                    @else
                                        <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                                            Gratis
                                        </span>
                                    @endif
                                </td>

                                {{-- Tombol pengaturan --}}
                                <td class="px-6 py-5 text-right">
                                    <a href="{{ route('admin.payments.edit', $competition) }}"
                                        class="inline-flex items-center rounded-lg bg-[#087B48] px-4 py-2.5 text-xs font-bold text-white transition hover:bg-[#065F38]">
                                        Atur Pembayaran
                                        <span class="ml-2" aria-hidden="true">→</span>
                                    </a>
                                </td>

                            </tr>

                        @empty

                            {{-- Tampilan ketika belum ada kompetisi --}}
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="mx-auto max-w-sm">
                                        <div class="text-4xl">🏆</div>

                                        <h3 class="mt-4 font-bold text-slate-800">
                                            Belum Ada Kompetisi
                                        </h3>

                                        <p class="mt-2 text-sm text-slate-500">
                                            Kompetisi yang tersedia akan muncul di halaman ini.
                                        </p>
                                    </div>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Navigasi halaman --}}
            @if ($competitions->hasPages())
                <div class="border-t border-slate-200 px-5 py-4 sm:px-6">
                    {{ $competitions->links() }}
                </div>
            @endif

        </div>

    </main>
@endsection
