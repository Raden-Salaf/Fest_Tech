@extends('layouts.app')

@section('title', 'Status Pendaftaran')

@section('content')
@php
    $requiresPayment = (bool) (
        $registration->competition->paymentSetting?->is_payment_required ?? false
    );

    $statusConfig = [
        'pending' => [
            'label' => 'Menunggu Verifikasi',
            'class' => 'bg-amber-100 text-amber-800',
        ],
        'approved' => [
            'label' => 'Disetujui',
            'class' => 'bg-emerald-100 text-emerald-800',
        ],
        'rejected' => [
            'label' => 'Ditolak',
            'class' => 'bg-red-100 text-red-800',
        ],
    ];

    $currentStatus = $registration->verification_status ?? 'pending';
    $currentStatusConfig = $statusConfig[$currentStatus] ?? [
        'label' => ucfirst($currentStatus),
        'class' => 'bg-gray-100 text-gray-700',
    ];

    $canResubmit = $currentStatus === 'rejected'
        && $latestSubmission
        && $latestSubmission->status === 'rejected';
@endphp

<div class="min-h-screen bg-gray-50 px-4 py-10">
    <div class="mx-auto max-w-4xl">

        {{-- Header --}}
        <div class="mb-8">
            <a
                href="{{ url('/kompetisi') }}"
                class="mb-5 inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-emerald-700"
            >
                <span>&larr;</span>
                Kembali ke kompetisi
            </a>

            <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">
                Status Pendaftaran
            </h1>

            <p class="mt-2 text-gray-600">
                Pantau proses verifikasi dan riwayat pengajuan kompetisimu.
            </p>
        </div>

        {{-- Competition information --}}
        <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-5">
                <p class="text-sm font-medium text-gray-500">
                    Kompetisi
                </p>

                <h2 class="mt-1 text-xl font-bold text-gray-900">
                    {{ $registration->competition->name }}
                </h2>
            </div>

            <div class="grid gap-5 px-6 py-5 sm:grid-cols-2">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Kode Pendaftaran
                    </p>
                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $registration->registration_code }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Tanggal Pendaftaran
                    </p>
                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $registration->registered_at?->format('d M Y, H:i') ?? $registration->created_at?->format('d M Y, H:i') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Nama Tim
                    </p>
                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $registration->team_name ?: 'Pendaftaran Individu' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Status Verifikasi
                    </p>

                    <span class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $currentStatusConfig['class'] }}">
                        {{ $currentStatusConfig['label'] }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Current status information --}}
        @if ($currentStatus === 'pending')
            <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-5">
                <div class="flex gap-3">
                    <div class="text-xl">⏳</div>
                    <div>
                        <h3 class="font-semibold text-amber-900">
                            Pengajuan sedang diperiksa
                        </h3>
                        <p class="mt-1 text-sm leading-relaxed text-amber-800">
                            Admin sedang memeriksa data pendaftaran
                            {{ $requiresPayment ? 'dan bukti pembayaran' : '' }}.
                            Silakan tunggu hasil verifikasi.
                        </p>
                    </div>
                </div>
            </div>
        @elseif ($currentStatus === 'approved')
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-5">
                <div class="flex gap-3">
                    <div class="text-xl">✓</div>
                    <div>
                        <h3 class="font-semibold text-emerald-900">
                            Pendaftaran berhasil diverifikasi
                        </h3>
                        <p class="mt-1 text-sm leading-relaxed text-emerald-800">
                            Selamat! Pendaftaranmu telah disetujui oleh admin.
                        </p>
                    </div>
                </div>
            </div>
        @elseif ($currentStatus === 'rejected')
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-5">
                <div class="flex gap-3">
                    <div class="text-xl">!</div>
                    <div class="min-w-0">
                        <h3 class="font-semibold text-red-900">
                            Pengajuan perlu diperbaiki
                        </h3>

                        <p class="mt-1 text-sm leading-relaxed text-red-800">
                            Admin menolak pengajuan terakhir. Perhatikan alasan
                            penolakan berikut sebelum mengajukan ulang.
                        </p>

                        @if ($latestSubmission?->rejection_reason)
                            <div class="mt-4 rounded-lg border border-red-200 bg-white p-4">
                                <p class="text-xs font-semibold uppercase tracking-wide text-red-500">
                                    Alasan Penolakan
                                </p>
                                <p class="mt-2 whitespace-pre-line text-sm text-gray-800">{{ $latestSubmission->rejection_reason }}</p>

                                @if ($latestSubmission->reviewer)
                                    <p class="mt-3 text-xs text-gray-500">
                                        Ditinjau oleh {{ $latestSubmission->reviewer->name }}
                                        @if ($latestSubmission->reviewed_at)
                                            pada {{ $latestSubmission->reviewed_at->format('d M Y, H:i') }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Submission history --}}
        <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-5">
                <h2 class="text-lg font-bold text-gray-900">
                    Riwayat Pengajuan
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Semua pengajuan tersimpan sebagai riwayat dan tidak akan dihapus.
                </p>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse ($registration->submissions as $submission)
                    @php
                        $submissionStatus = $statusConfig[$submission->status] ?? [
                            'label' => ucfirst($submission->status),
                            'class' => 'bg-gray-100 text-gray-700',
                        ];
                    @endphp

                    <div class="px-6 py-5">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="font-semibold text-gray-900">
                                    Pengajuan #{{ $submission->submission_number }}
                                </h3>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $submission->created_at?->format('d M Y, H:i') }}
                                </p>
                            </div>

                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $submissionStatus['class'] }}">
                                {{ $submissionStatus['label'] }}
                            </span>
                        </div>

                        @if ($submission->payment_description)
                            <div class="mt-4">
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                    Keterangan Pembayaran
                                </p>
                                <p class="mt-1 whitespace-pre-line text-sm text-gray-700">
                                    {{ $submission->payment_description }}
                                </p>
                            </div>
                        @endif

                        @if ($submission->appeal_message)
                            <div class="mt-4">
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                    Pesan Pengajuan Ulang
                                </p>
                                <p class="mt-1 whitespace-pre-line text-sm text-gray-700">
                                    {{ $submission->appeal_message }}
                                </p>
                            </div>
                        @endif

                        @if ($submission->rejection_reason)
                            <div class="mt-4 rounded-lg bg-red-50 p-3">
                                <p class="text-xs font-semibold uppercase tracking-wide text-red-600">
                                    Alasan Penolakan
                                </p>
                                <p class="mt-1 whitespace-pre-line text-sm text-red-800">
                                    {{ $submission->rejection_reason }}
                                </p>
                            </div>
                        @endif

                        @if ($submission->reviewer)
                            <p class="mt-3 text-xs text-gray-500">
                                Ditinjau oleh {{ $submission->reviewer->name }}
                                @if ($submission->reviewed_at)
                                    pada {{ $submission->reviewed_at->format('d M Y, H:i') }}
                                @endif
                            </p>
                        @elseif ($submission->status === 'pending')
                            <p class="mt-3 text-xs text-amber-600">
                                Menunggu pemeriksaan admin.
                            </p>
                        @endif
                    </div>
                @empty
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm text-gray-500">
                            Belum ada riwayat pengajuan verifikasi.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Resubmission form --}}
        @if ($canResubmit)
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-5">
                    <h2 class="text-lg font-bold text-gray-900">
                        Ajukan Ulang
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Perbaiki pengajuanmu sesuai alasan penolakan admin.
                    </p>
                </div>

                <form
                    action="{{ route('pendaftaran.resubmit', $registration) }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-5 p-6"
                >
                    @csrf

                    @if ($requiresPayment)
                        <div>
                            <label for="payment_description" class="mb-2 block text-sm font-semibold text-gray-700">
                                Keterangan Pembayaran
                            </label>

                            <textarea
                                id="payment_description"
                                name="payment_description"
                                rows="3"
                                placeholder="Contoh: Transfer melalui BCA atas nama..."
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                            >{{ old('payment_description') }}</textarea>

                            @error('payment_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_proof" class="mb-2 block text-sm font-semibold text-gray-700">
                                Bukti Pembayaran Baru
                            </label>

                            <input
                                id="payment_proof"
                                type="file"
                                name="payment_proof"
                                accept=".jpg,.jpeg,.png,.webp,.pdf"
                                required
                                class="block w-full cursor-pointer rounded-xl border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:cursor-pointer file:border-0 file:bg-emerald-50 file:px-4 file:py-3 file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100"
                            />

                            <p class="mt-2 text-xs text-gray-500">
                                Format JPG, JPEG, PNG, WEBP, atau PDF. Maksimal 5 MB.
                            </p>

                            @error('payment_proof')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <div>
                            <label for="appeal_message" class="mb-2 block text-sm font-semibold text-gray-700">
                                Pesan Perbaikan
                            </label>

                            <textarea
                                id="appeal_message"
                                name="appeal_message"
                                rows="5"
                                required
                                minlength="5"
                                maxlength="2000"
                                placeholder="Jelaskan perbaikan yang sudah kamu lakukan..."
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                            >{{ old('appeal_message') }}</textarea>

                            @error('appeal_message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if ($requiresPayment)
                        <div>
                            <label for="appeal_message" class="mb-2 block text-sm font-semibold text-gray-700">
                                Pesan Tambahan (Opsional)
                            </label>

                            <textarea
                                id="appeal_message"
                                name="appeal_message"
                                rows="3"
                                maxlength="2000"
                                placeholder="Tambahkan keterangan jika diperlukan..."
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                            >{{ old('appeal_message') }}</textarea>

                            @error('appeal_message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div class="rounded-xl bg-gray-50 p-4 text-sm text-gray-600">
                        <p class="font-semibold text-gray-800">
                            Perlu diperhatikan
                        </p>
                        <p class="mt-1 leading-relaxed">
                            Pengajuan sebelumnya tetap tersimpan. Setelah kamu mengirim
                            pengajuan ulang, status verifikasi akan kembali menjadi
                            menunggu pemeriksaan admin.
                        </p>
                    </div>

                    <button
                        type="submit"
                        class="w-full cursor-pointer rounded-xl bg-emerald-700 px-5 py-3 font-semibold text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                    >
                        Kirim Pengajuan Ulang
                    </button>
                </form>
            </div>
        @endif

    </div>
</div>
@endsection
