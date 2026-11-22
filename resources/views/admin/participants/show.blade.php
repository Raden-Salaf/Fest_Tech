@extends('layouts.admin')

@section('title', 'Detail Peserta - Festival Teknologi')

@section('content')
@php
    $verificationClasses = [
        'pending' => 'bg-amber-100 text-amber-700',
        'approved' => 'bg-emerald-100 text-emerald-700',
        'rejected' => 'bg-red-100 text-red-700',
    ];

    $verificationLabels = [
        'pending' => 'Menunggu Verifikasi',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
    ];

    $latestSubmission = $registration->submissions
        ->sortByDesc('submission_number')
        ->first();

    $isPaymentRequired = $registration->competition->paymentSetting?->is_payment_required ?? false;

    $canReview = $registration->verification_status !== 'approved'
        && (
            ! $isPaymentRequired
            || (
                $latestSubmission
                && $latestSubmission->status === 'pending'
                && $latestSubmission->payment_proof_path
            )
        );
@endphp

<main class="px-5 py-8 md:px-8">
    <div class="mx-auto max-w-6xl">

        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('admin.participants.index') }}"
                class="inline-flex cursor-pointer items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-emerald-700">
                <span>&larr;</span>
                Kembali ke daftar peserta
            </a>

            <div class="mt-5 flex flex-col justify-between gap-4 md:flex-row md:items-start">
                <div>
                    <p class="text-sm font-medium text-emerald-700">
                        Detail Pendaftaran
                    </p>

                    <h1 class="mt-1 text-2xl font-bold text-slate-900 md:text-3xl">
                        {{ $registration->user->name }}
                    </h1>

                    <p class="mt-2 text-sm text-slate-500">
                        Kode: {{ $registration->registration_code }}
                    </p>
                </div>

                <span class="inline-flex w-fit rounded-full px-4 py-2 text-sm font-semibold {{ $verificationClasses[$registration->verification_status] ?? 'bg-slate-100 text-slate-600' }}">
                    {{ $verificationLabels[$registration->verification_status] ?? ucfirst($registration->verification_status) }}
                </span>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <p class="font-semibold">Tidak dapat memproses tindakan.</p>

                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Kolom informasi peserta --}}
            <div class="space-y-6 lg:col-span-2">

                {{-- Identitas peserta --}}
                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-slate-900">
                        Informasi Peserta
                    </h2>

                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        <div>
                            <p class="text-xs font-medium uppercase text-slate-400">
                                Nama Perwakilan
                            </p>
                            <p class="mt-1 font-semibold text-slate-800">
                                {{ $registration->user->name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-slate-400">
                                Email
                            </p>
                            <p class="mt-1 break-all font-semibold text-slate-800">
                                {{ $registration->user->email }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-slate-400">
                                Kompetisi
                            </p>
                            <p class="mt-1 font-semibold text-slate-800">
                                {{ $registration->competition->name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-slate-400">
                                Tanggal Pendaftaran
                            </p>
                            <p class="mt-1 font-semibold text-slate-800">
                                {{ $registration->registered_at?->format('d F Y, H:i') ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-slate-400">
                                Jenis Kompetisi
                            </p>
                            <p class="mt-1 font-semibold text-slate-800">
                                {{ $registration->competition->type === 'team' ? 'Tim' : 'Individu' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-slate-400">
                                Status Pendaftaran
                            </p>
                            <p class="mt-1 font-semibold text-slate-800">
                                {{ ucfirst($registration->status) }}
                            </p>
                        </div>
                    </div>
                </section>

                {{-- Data tim --}}
                @if ($registration->competition->type === 'team')
                    <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h2 class="font-semibold text-slate-900">
                            Informasi Tim
                        </h2>

                        <div class="mt-4">
                            <p class="text-xs font-medium uppercase text-slate-400">
                                Nama Tim
                            </p>
                            <p class="mt-1 text-lg font-bold text-slate-800">
                                {{ $registration->team_name ?? '-' }}
                            </p>
                        </div>

                        <div class="mt-5">
                            <p class="mb-3 text-sm font-semibold text-slate-700">
                                Anggota Tambahan
                            </p>

                            @forelse ($registration->members as $index => $member)
                                <div class="flex items-center gap-3 border-t border-slate-100 py-3">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-xs font-bold text-emerald-700">
                                        {{ $index + 2 }}
                                    </span>

                                    <span class="font-medium text-slate-700">
                                        {{ $member->name }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">
                                    Tidak ada anggota tambahan.
                                </p>
                            @endforelse
                        </div>
                    </section>
                @endif

                {{-- Jawaban formulir dinamis --}}
                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-slate-900">
                        Jawaban Formulir Pendaftaran
                    </h2>

                    <div class="mt-4 divide-y divide-slate-100">
                        @forelse ($registration->answers as $answer)
                            <div class="py-4">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    {{ $answer->formField?->label ?? 'Pertanyaan tidak tersedia' }}
                                </p>

                                @php
                                    $answerValue = $answer->answer;

                                    if ($answer->formField?->type === 'checkbox') {
                                        $decodedAnswer = json_decode($answerValue, true);

                                        if (is_array($decodedAnswer)) {
                                            $answerValue = implode(', ', $decodedAnswer);
                                        }
                                    }
                                @endphp

                                <p class="mt-2 whitespace-pre-line break-words text-sm font-medium text-slate-800">
                                    {{ filled($answerValue) ? $answerValue : '-' }}
                                </p>
                            </div>
                        @empty
                            <p class="py-5 text-sm text-slate-500">
                                Tidak ada jawaban formulir tambahan.
                            </p>
                        @endforelse
                    </div>
                </section>

                {{-- Riwayat pengajuan --}}
                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-slate-900">
                        Riwayat Pengajuan dan Verifikasi
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        Semua pengajuan dan hasil pemeriksaan tersimpan di sini.
                    </p>

                    <div class="mt-5 space-y-4">
                        @forelse ($registration->submissions->sortByDesc('submission_number') as $submission)
                            <div class="rounded-xl border border-slate-200 p-4">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            Pengajuan #{{ $submission->submission_number }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $submission->created_at?->format('d M Y, H:i') }}
                                        </p>
                                    </div>

                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $verificationClasses[$submission->status] ?? 'bg-slate-100 text-slate-600' }}">
                                        {{ $verificationLabels[$submission->status] ?? ucfirst($submission->status) }}
                                    </span>
                                </div>

                                @if ($submission->payment_description)
                                    <div class="mt-4">
                                        <p class="text-xs font-medium text-slate-400">
                                            Keterangan Pembayaran
                                        </p>

                                        <p class="mt-1 whitespace-pre-line text-sm text-slate-700">
                                            {{ $submission->payment_description }}
                                        </p>
                                    </div>
                                @endif

                                @if ($submission->appeal_message)
                                    <div class="mt-4 rounded-lg bg-blue-50 p-3">
                                        <p class="text-xs font-semibold text-blue-700">
                                            Pesan Sanggahan Peserta
                                        </p>

                                        <p class="mt-1 whitespace-pre-line text-sm text-blue-900">
                                            {{ $submission->appeal_message }}
                                        </p>
                                    </div>
                                @endif

                                @if ($submission->payment_proof_path)
                                    <div class="mt-4">
                                        <a href="{{ route('admin.participants.submissions.proof', $submission) }}"
                                            target="_blank"
                                            rel="noopener"
                                            class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-emerald-200 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-50">
                                            Lihat Bukti Pembayaran
                                        </a>
                                    </div>
                                @endif

                                @if ($submission->rejection_reason)
                                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-3">
                                        <p class="text-xs font-semibold text-red-700">
                                            Alasan Penolakan
                                        </p>

                                        <p class="mt-1 whitespace-pre-line text-sm text-red-800">
                                            {{ $submission->rejection_reason }}
                                        </p>
                                    </div>
                                @endif

                                @if ($submission->reviewer)
                                    <p class="mt-4 text-xs text-slate-500">
                                        Diperiksa oleh {{ $submission->reviewer->name }}
                                        pada {{ $submission->reviewed_at?->format('d M Y, H:i') }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            <p class="py-5 text-sm text-slate-500">
                                Belum ada riwayat pengajuan pemeriksaan.
                            </p>
                        @endforelse
                    </div>
                </section>
            </div>

            {{-- Panel tindakan admin --}}
            <aside class="space-y-6">
                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-slate-900">
                        Pemeriksaan Pendaftaran
                    </h2>

                    <div class="mt-4 rounded-lg bg-slate-50 p-4">
                        <p class="text-xs text-slate-500">
                            Jenis Pembayaran
                        </p>

                        <p class="mt-1 font-semibold text-slate-800">
                            {{ $isPaymentRequired ? 'Berbayar' : 'Gratis' }}
                        </p>

                        @if ($isPaymentRequired)
                            <p class="mt-2 text-lg font-bold text-emerald-700">
                                Rp {{ number_format($registration->competition->paymentSetting->fee_amount ?? 0, 0, ',', '.') }}
                            </p>
                        @endif
                    </div>

                    @if ($registration->verification_status === 'approved')
                        <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                            Pendaftaran ini sudah disetujui.
                        </div>

                    @elseif ($canReview)

                        {{-- Form persetujuan --}}
                        <form action="{{ route('admin.participants.approve', $registration) }}"
                            method="POST"
                            class="mt-5"
                            data-swal-confirm="approve">

                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                class="w-full cursor-pointer rounded-lg bg-emerald-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800">
                                Setujui Pendaftaran
                            </button>
                        </form>

                        <div class="my-5 border-t border-slate-100"></div>

                        {{-- Form penolakan --}}
                        <form action="{{ route('admin.participants.reject', $registration) }}"
                            method="POST"
                            data-swal-confirm="reject">

                            @csrf
                            @method('PATCH')

                            <label for="rejection_reason"
                                class="mb-2 block text-sm font-semibold text-slate-700">
                                Alasan Penolakan
                            </label>

                            <textarea
                                name="rejection_reason"
                                id="rejection_reason"
                                rows="5"
                                required
                                minlength="5"
                                maxlength="2000"
                                placeholder="Jelaskan alasan penolakan agar peserta dapat memperbaiki pengajuannya..."
                                class="w-full rounded-lg border border-slate-300 px-3 py-3 text-sm outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-100"
                            >{{ old('rejection_reason') }}</textarea>

                            @error('rejection_reason')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                            <button type="submit"
                                class="mt-3 w-full cursor-pointer rounded-lg bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                                Tolak Pengajuan
                            </button>
                        </form>

                    @else
                        <div class="mt-5 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                            Belum ada pengajuan terbaru yang dapat diperiksa. Tunggu peserta mengirimkan bukti atau pengajuan baru.
                        </div>
                    @endif
                </section>
            </aside>
        </div>
    </div>
</main>

{{-- SweetAlert konfirmasi tindakan --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const confirmationForms = document.querySelectorAll('[data-swal-confirm]');

    confirmationForms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            if (typeof window.Swal === 'undefined') {
                console.error('SweetAlert2 belum tersedia. Periksa pemuatan SweetAlert pada layout admin.');
                return;
            }

            const action = form.dataset.swalConfirm;

            let options = {};

            if (action === 'approve') {
                options = {
                    title: 'Setujui pendaftaran?',
                    text: 'Peserta ini akan dinyatakan lolos verifikasi pendaftaran.',
                    icon: 'question',
                    confirmButtonText: 'Ya, Setujui',
                    confirmButtonColor: '#047857',
                    cancelButtonText: 'Batal',
                    showCancelButton: true,
                    reverseButtons: true
                };
            }

            if (action === 'reject') {
                const reason = form.querySelector('[name="rejection_reason"]').value.trim();

                if (reason.length < 5) {
                    return;
                }

                options = {
                    title: 'Tolak pengajuan peserta?',
                    text: 'Alasan penolakan akan disimpan dalam riwayat pemeriksaan dan dapat dilihat peserta.',
                    icon: 'warning',
                    confirmButtonText: 'Ya, Tolak Pengajuan',
                    confirmButtonColor: '#dc2626',
                    cancelButtonText: 'Periksa Kembali',
                    showCancelButton: true,
                    reverseButtons: true
                };
            }

            window.Swal.fire(options).then(function (result) {
                if (result.isConfirmed) {
                    form.dataset.confirmed = 'true';
                    HTMLFormElement.prototype.submit.call(form);
                }
            });
        });
    });
});
</script>
@endsection
