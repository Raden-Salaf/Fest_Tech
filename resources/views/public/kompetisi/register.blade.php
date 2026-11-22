@extends('layouts.app')

@section('title', 'Pendaftaran ' . $competition->name)

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-10 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-4xl">

        {{-- Header halaman --}}
        <div class="mb-8">
            <a
                href="{{ route('kompetisi.show', $competition->slug) }}"
                class="mb-5 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 transition hover:text-emerald-900 cursor-pointer"
            >
                <span aria-hidden="true">&larr;</span>
                Kembali ke detail kompetisi
            </a>

            <div class="rounded-2xl bg-[#06251D] p-6 text-white shadow-lg sm:p-8">
                <p class="mb-2 text-sm font-semibold uppercase tracking-[0.2em] text-emerald-300">
                    Festival Teknologi UNUSIA
                </p>

                <h1 class="text-2xl font-bold sm:text-3xl">
                    Formulir Pendaftaran
                </h1>

                <p class="mt-2 text-sm text-emerald-100 sm:text-base">
                    {{ $competition->name }}
                </p>

                <p class="mt-4 max-w-2xl text-sm leading-relaxed text-white/75">
                    Lengkapi seluruh informasi di bawah ini dengan benar.
                    Pastikan data yang kamu masukkan sudah sesuai sebelum mengirim pendaftaran.
                </p>
            </div>
        </div>

        {{-- Notifikasi validasi --}}
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-5">
                <div class="flex items-start gap-3">
                    <div class="text-red-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v4m0 4h.01M10.29 3.86l-8.1 14.02A2 2 0 003.92 21h16.16a2 2 0 001.73-3.12l-8.1-14.02a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>

                    <div>
                        <h3 class="font-bold text-red-800">
                            Terdapat kesalahan pada formulir
                        </h3>

                        <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Form utama --}}
        <form
            action="{{ route('kompetisi.register.store', $competition->slug) }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf

            {{-- Informasi peserta --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-bold text-slate-900">
                        Informasi Peserta
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Informasi akun dan tim yang mengikuti kompetisi.
                    </p>
                </div>

                <div class="space-y-5 p-6">

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Nama Ketua / Perwakilan
                        </label>

                        <input
                            type="text"
                            value="{{ auth()->user()->name }}"
                            disabled
                            class="w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-500"
                        >

                        <p class="mt-1 text-xs text-slate-500">
                            Nama diambil dari akun yang sedang login.
                        </p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Email Ketua / Perwakilan
                        </label>

                        <input
                            type="email"
                            value="{{ auth()->user()->email }}"
                            disabled
                            class="w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-500"
                        >

                        <p class="mt-1 text-xs text-slate-500">
                            Email diambil dari akun yang sedang login.
                        </p>
                    </div>

                    @if ($competition->type === 'team')
                        <div>
                            <label for="team_name" class="mb-2 block text-sm font-semibold text-slate-700">
                                Nama Tim <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="team_name"
                                name="team_name"
                                value="{{ old('team_name') }}"
                                placeholder="Masukkan nama tim"
                                required
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                            >

                            @error('team_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="mb-3">
                                <label class="block text-sm font-semibold text-slate-700">
                                    Anggota Tim
                                </label>

                                <p class="mt-1 text-xs text-slate-500">
                                    Ketua tim otomatis dihitung sebagai satu anggota.
                                    Masukkan nama anggota lainnya di bawah.
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    Minimal {{ $competition->min_team_members ?? 1 }}
                                    dan maksimal {{ $competition->max_team_members ?? 10 }}
                                    orang termasuk ketua.
                                </p>
                            </div>

                            <div id="members-container" class="space-y-3">
                                @php
                                    $oldMembers = old('members', ['']);
                                @endphp

                                @foreach ($oldMembers as $index => $member)
                                    <div class="member-row flex items-center gap-2">
                                        <input
                                            type="text"
                                            name="members[]"
                                            value="{{ $member }}"
                                            placeholder="Nama anggota tim"
                                            required
                                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                                        >

                                        <button
                                            type="button"
                                            onclick="removeMember(this)"
                                            class="rounded-xl border border-red-200 px-4 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50 cursor-pointer"
                                            aria-label="Hapus anggota"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            <button
                                type="button"
                                id="add-member"
                                class="mt-4 inline-flex items-center gap-2 rounded-xl border border-emerald-600 px-4 py-2.5 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-50 cursor-pointer"
                            >
                                <span class="text-lg">+</span>
                                Tambah Anggota
                            </button>

                            @error('members')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            @error('members.*')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                </div>
            </section>

            {{-- Form dinamis kompetisi --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-bold text-slate-900">
                        Data Tambahan
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Lengkapi pertanyaan tambahan dari panitia kompetisi.
                    </p>
                </div>

                <div class="space-y-6 p-6">
                    @forelse ($formFields as $field)
                        @php
                            // Dot notation untuk Laravel old() dan @error.
                            $fieldName = 'answers.' . $field->id;

                            // Bracket notation untuk nama input HTML.
                            // Ini membuat PHP mengirim nested array answers.
                            $fieldInputName = 'answers[' . $field->id . ']';

                            $fieldOptions = is_array($field->options)
                                ? $field->options
                                : [];

                            $fieldValue = old($fieldName);
                        @endphp

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                {{ $field->label }}

                                @if ($field->is_required)
                                    <span class="text-red-500">*</span>
                                @else
                                    <span class="font-normal text-slate-400">(Opsional)</span>
                                @endif
                            </label>

                            @if ($field->type === 'textarea')
                                <textarea
                                    name="{{ $fieldInputName }}"
                                    rows="4"
                                    placeholder="{{ $field->placeholder }}"
                                    @if ($field->is_required) required @endif
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                                >{{ $fieldValue }}</textarea>

                            @elseif ($field->type === 'select')
                                <select
                                    name="{{ $fieldInputName }}"
                                    @if ($field->is_required) required @endif
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 cursor-pointer"
                                >
                                    <option value="">Pilih {{ $field->label }}</option>

                                    @foreach ($fieldOptions as $option)
                                        <option
                                            value="{{ $option }}"
                                            @selected((string) $fieldValue === (string) $option)
                                        >
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>

                            @elseif ($field->type === 'radio')
                                <div class="space-y-3">
                                    @foreach ($fieldOptions as $option)
                                        <label class="flex items-center gap-3 text-sm text-slate-700 cursor-pointer">
                                            <input
                                                type="radio"
                                                name="{{ $fieldInputName }}"
                                                value="{{ $option }}"
                                                @checked((string) $fieldValue === (string) $option)
                                                @if ($field->is_required) required @endif
                                                class="h-4 w-4 accent-emerald-600 cursor-pointer"
                                            >

                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif ($field->type === 'checkbox')
                                <div class="space-y-3">
                                    @foreach ($fieldOptions as $option)
                                        <label class="flex items-center gap-3 text-sm text-slate-700 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                name="{{ $fieldInputName }}[]"
                                                value="{{ $option }}"
                                                @checked(
                                                    is_array($fieldValue)
                                                    && in_array($option, $fieldValue)
                                                )
                                                class="h-4 w-4 rounded accent-emerald-600 cursor-pointer"
                                            >

                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            @else
                                <input
                                    type="{{ in_array($field->type, ['text', 'email', 'number', 'tel', 'url', 'date']) ? $field->type : 'text' }}"
                                    name="{{ $fieldInputName }}"
                                    value="{{ $fieldValue }}"
                                    placeholder="{{ $field->placeholder }}"
                                    @if ($field->is_required) required @endif
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                                >
                            @endif

                            @error($fieldName)
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            @error($fieldName . '.*')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @empty
                        <div class="rounded-xl bg-slate-50 p-5 text-center">
                            <p class="text-sm text-slate-500">
                                Belum ada pertanyaan tambahan dari panitia.
                            </p>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- Pembayaran --}}
            @if ($requiresPayment)
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-5">
                        <h2 class="text-lg font-bold text-slate-900">
                            Informasi Pembayaran
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Silakan lakukan pembayaran sesuai instruksi panitia.
                        </p>
                    </div>

                    <div class="space-y-5 p-6">
                        <div class="rounded-xl bg-emerald-50 p-5">
                            <p class="text-sm font-medium text-emerald-800">
                                Biaya pendaftaran
                            </p>

                            <p class="mt-1 text-2xl font-bold text-emerald-900">
                                Rp {{ number_format($paymentSetting->fee_amount, 0, ',', '.') }}
                            </p>
                        </div>

                        @if ($paymentSetting->description)
                            <div>
                                <h3 class="mb-2 text-sm font-semibold text-slate-800">
                                    Keterangan Pembayaran
                                </h3>

                                <p class="text-sm leading-relaxed text-slate-600">
                                    {{ $paymentSetting->description }}
                                </p>
                            </div>
                        @endif

                        @if ($paymentSetting->instructions)
                            <div>
                                <h3 class="mb-2 text-sm font-semibold text-slate-800">
                                    Instruksi Pembayaran
                                </h3>

                                <div class="rounded-xl bg-slate-50 p-4 text-sm leading-relaxed text-slate-700">
                                    {!! nl2br(e($paymentSetting->instructions)) !!}
                                </div>
                            </div>
                        @endif

                        <div>
                            <h3 class="mb-3 text-sm font-semibold text-slate-800">
                                Metode Pembayaran
                            </h3>

                            <div class="space-y-3">
                                @foreach ($paymentSetting->paymentMethods as $method)
                                    <div class="rounded-xl border border-slate-200 p-4">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <p class="font-semibold text-slate-900">
                                                    {{ $method->provider_name }}
                                                </p>

                                                <p class="mt-1 text-xs uppercase tracking-wide text-slate-500">
                                                    {{ $method->method_type }}
                                                </p>

                                                @if ($method->account_name)
                                                    <p class="mt-3 text-sm text-slate-600">
                                                        Atas nama:
                                                        <span class="font-semibold text-slate-800">
                                                            {{ $method->account_name }}
                                                        </span>
                                                    </p>
                                                @endif

                                                @if ($method->account_number)
                                                    <div class="mt-1 flex flex-wrap items-center gap-2">
                                                        <p class="text-sm text-slate-600">
                                                            Nomor:
                                                            <span class="font-semibold text-slate-900">
                                                                {{ $method->account_number }}
                                                            </span>
                                                        </p>

                                                        <button
                                                            type="button"
                                                            onclick="copyPaymentNumber('{{ $method->account_number }}')"
                                                            class="text-xs font-semibold text-emerald-700 hover:text-emerald-900 cursor-pointer"
                                                        >
                                                            Salin nomor
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>

                                            @if ($method->image_path)
                                                <img
                                                    src="{{ asset('storage/' . $method->image_path) }}"
                                                    alt="QR atau informasi pembayaran {{ $method->provider_name }}"
                                                    class="h-36 w-36 rounded-lg border border-slate-200 object-contain"
                                                >
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label for="payment_description" class="mb-2 block text-sm font-semibold text-slate-700">
                                Keterangan Pembayaran
                            </label>

                            <textarea
                                id="payment_description"
                                name="payment_description"
                                rows="3"
                                placeholder="Contoh: Transfer dari rekening atas nama ..."
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                            >{{ old('payment_description') }}</textarea>

                            @error('payment_description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_proof" class="mb-2 block text-sm font-semibold text-slate-700">
                                Bukti Pembayaran <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="file"
                                id="payment_proof"
                                name="payment_proof"
                                accept=".jpg,.jpeg,.png,.webp,.pdf"
                                required
                                class="block w-full rounded-xl border border-slate-300 text-sm text-slate-600 file:mr-4 file:rounded-l-xl file:border-0 file:bg-emerald-50 file:px-4 file:py-3 file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer"
                            >

                            <p class="mt-2 text-xs text-slate-500">
                                Format JPG, JPEG, PNG, WEBP, atau PDF. Maksimal 5 MB.
                            </p>

                            @error('payment_proof')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>
            @endif

            {{-- Persetujuan dan submit --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="rounded-xl bg-amber-50 p-4">
                    <p class="text-sm leading-relaxed text-amber-800">
                        Pastikan seluruh informasi sudah benar. Setelah formulir dikirim,
                        pendaftaran akan masuk ke tahap pemeriksaan oleh panitia.
                    </p>
                </div>

                <label class="mt-5 flex items-start gap-3 text-sm text-slate-700 cursor-pointer">
                    <input
                        type="checkbox"
                        id="registration_agreement"
                        required
                        class="mt-1 h-4 w-4 accent-emerald-600 cursor-pointer"
                    >

                    <span>
                        Saya menyatakan bahwa informasi yang diberikan benar dan
                        bersedia mengikuti ketentuan kompetisi.
                    </span>
                </label>

                <button
                    type="submit"
                    class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-6 py-4 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 cursor-pointer"
                >
                    Kirim Pendaftaran
                    <span aria-hidden="true">&rarr;</span>
                </button>

                <p class="mt-3 text-center text-xs text-slate-400">
                    Festival Teknologi UNUSIA
                </p>
            </section>
        </form>
    </div>
</div>

{{-- Script interaksi form --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Batas anggota tambahan, dikurangi ketua tim.
        const minimumMembers = {{ max(0, (int) ($competition->min_team_members ?? 1) - 1) }};
        const maximumMembers = {{ max(0, (int) ($competition->max_team_members ?? 10) - 1) }};

        const container = document.getElementById('members-container');
        const addButton = document.getElementById('add-member');

        // Hitung jumlah anggota tambahan yang sedang ditampilkan.
        function updateMemberButtons() {
            if (!container || !addButton) {
                return;
            }

            const rows = container.querySelectorAll('.member-row');

            addButton.disabled = rows.length >= maximumMembers;
            addButton.classList.toggle('opacity-50', addButton.disabled);
            addButton.classList.toggle('cursor-not-allowed', addButton.disabled);

            // Anggota minimum tidak boleh dihapus.
            rows.forEach((row, index) => {
                const removeButton = row.querySelector('button');

                if (removeButton) {
                    removeButton.disabled = rows.length <= minimumMembers;
                    removeButton.classList.toggle(
                        'opacity-40',
                        removeButton.disabled
                    );
                    removeButton.classList.toggle(
                        'cursor-not-allowed',
                        removeButton.disabled
                    );
                }
            });
        }

        // Tambahkan input anggota baru.
        if (addButton && container) {
            addButton.addEventListener('click', function () {
                const currentCount = container.querySelectorAll('.member-row').length;

                if (currentCount >= maximumMembers) {
                    return;
                }

                const row = document.createElement('div');

                row.className = 'member-row flex items-center gap-2';

                row.innerHTML = `
                    <input
                        type="text"
                        name="members[]"
                        placeholder="Nama anggota tim"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >

                    <button
                        type="button"
                        onclick="removeMember(this)"
                        class="rounded-xl border border-red-200 px-4 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50 cursor-pointer"
                        aria-label="Hapus anggota"
                    >
                        Hapus
                    </button>
                `;

                container.appendChild(row);
                updateMemberButtons();
            });

            updateMemberButtons();
        }
    });

    // Hapus input anggota.
    function removeMember(button) {
        const container = document.getElementById('members-container');

        if (!container) {
            return;
        }

        const rows = container.querySelectorAll('.member-row');
        const minimumMembers = {{ max(0, (int) ($competition->min_team_members ?? 1) - 1) }};

        if (rows.length <= minimumMembers) {
            return;
        }

        button.closest('.member-row').remove();

        // Perbarui kondisi tombol tambah anggota.
        const addButton = document.getElementById('add-member');

        if (addButton) {
            const currentCount = container.querySelectorAll('.member-row').length;

            addButton.disabled = currentCount >= {{ max(0, (int) ($competition->max_team_members ?? 10) - 1) }};
            addButton.classList.toggle('opacity-50', addButton.disabled);
            addButton.classList.toggle('cursor-not-allowed', addButton.disabled);
        }

        const currentRows = container.querySelectorAll('.member-row');

        currentRows.forEach((row) => {
            const removeButton = row.querySelector('button');

            if (removeButton) {
                removeButton.disabled = currentRows.length <= minimumMembers;
                removeButton.classList.toggle(
                    'opacity-40',
                    removeButton.disabled
                );
                removeButton.classList.toggle(
                    'cursor-not-allowed',
                    removeButton.disabled
                );
            }
        });
    }

    // Salin nomor rekening atau pembayaran.
    function copyPaymentNumber(number) {
        navigator.clipboard.writeText(number)
            .then(() => {
                alert('Nomor pembayaran berhasil disalin.');
            })
            .catch(() => {
                alert('Tidak dapat menyalin nomor pembayaran.');
            });
    }
</script>
@endsection
