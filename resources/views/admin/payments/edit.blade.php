@extends('layouts.admin')

@section('content')
    @php
        // Ambil pengaturan pembayaran kompetisi.
        $setting = $competition->paymentSetting;

        // Tentukan apakah kompetisi menggunakan pembayaran.
        $isPaid = (bool) old(
            'is_payment_required',
            $setting?->is_payment_required ?? false
        );
    @endphp

    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Header halaman --}}
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Pengaturan Pembayaran
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Atur biaya dan metode pembayaran untuk
                    {{ $competition->name }}.
                </p>
            </div>

            <a
                href="{{ route('admin.payments.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                Kembali
            </a>
        </div>

        {{-- Notifikasi sukses --}}
        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Notifikasi validasi --}}
        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                <p class="font-semibold text-red-700">
                    Ada data yang perlu diperbaiki:
                </p>

                <ul class="mt-2 list-inside list-disc text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Informasi kompetisi --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Kompetisi
            </p>

            <h2 class="mt-1 text-lg font-bold text-slate-800">
                {{ $competition->name }}
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Slug: {{ $competition->slug }}
            </p>
        </div>

        {{-- Form pengaturan biaya --}}
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-5">
                <h2 class="text-lg font-bold text-slate-800">
                    Pengaturan Biaya Pendaftaran
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Tentukan apakah kompetisi ini gratis atau berbayar.
                </p>
            </div>

            <form
                action="{{ route('admin.payments.update', $competition) }}"
                method="POST"
                class="space-y-5"
            >
                @csrf
                @method('PUT')

                {{-- Pilihan gratis atau berbayar --}}
                <div>
                    <label class="mb-3 block text-sm font-semibold text-slate-700">
                        Jenis Pembayaran
                    </label>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 p-4 transition hover:border-green-500">
                            <input
                                type="radio"
                                name="is_payment_required"
                                value="0"
                                {{ !$isPaid ? 'checked' : '' }}
                                class="h-4 w-4 text-green-600 focus:ring-green-500"
                            >

                            <div>
                                <p class="font-semibold text-slate-800">
                                    Gratis
                                </p>

                                <p class="text-xs text-slate-500">
                                    Tidak ada biaya pendaftaran.
                                </p>
                            </div>
                        </label>

                        <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 p-4 transition hover:border-green-500">
                            <input
                                type="radio"
                                name="is_payment_required"
                                value="1"
                                {{ $isPaid ? 'checked' : '' }}
                                class="h-4 w-4 text-green-600 focus:ring-green-500"
                            >

                            <div>
                                <p class="font-semibold text-slate-800">
                                    Berbayar
                                </p>

                                <p class="text-xs text-slate-500">
                                    Peserta wajib melakukan pembayaran.
                                </p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Nominal biaya --}}
                <div id="fee-field" class="{{ $isPaid ? '' : 'hidden' }}">
                    <label for="fee_amount" class="mb-2 block text-sm font-semibold text-slate-700">
                        Biaya Pendaftaran (Rupiah)
                    </label>

                    <input
                        type="number"
                        name="fee_amount"
                        id="fee_amount"
                        min="1"
                        step="1"
                        value="{{ old('fee_amount', $setting?->fee_amount ?? '') }}"
                        placeholder="Contoh: 50000"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-2 focus:ring-green-100"
                    >

                    <p class="mt-1 text-xs text-slate-500">
                        Masukkan nominal tanpa titik atau simbol mata uang.
                    </p>
                </div>

                {{-- Deskripsi pembayaran --}}
                <div id="description-field" class="{{ $isPaid ? '' : 'hidden' }}">
                    <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">
                        Deskripsi Pembayaran
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="3"
                        placeholder="Contoh: Biaya pendaftaran sudah termasuk fasilitas kompetisi."
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-2 focus:ring-green-100"
                    >{{ old('description', $setting?->description) }}</textarea>
                </div>

                {{-- Instruksi pembayaran --}}
                <div id="instructions-field" class="{{ $isPaid ? '' : 'hidden' }}">
                    <label for="instructions" class="mb-2 block text-sm font-semibold text-slate-700">
                        Instruksi Pembayaran
                    </label>

                    <textarea
                        name="instructions"
                        id="instructions"
                        rows="4"
                        placeholder="Tuliskan instruksi pembayaran untuk peserta..."
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-green-600 focus:ring-2 focus:ring-green-100"
                    >{{ old('instructions', $setting?->instructions) }}</textarea>
                </div>

                <div class="flex justify-end border-t border-slate-100 pt-4">
                    <button
                        type="submit"
                        class="rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800"
                    >
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </section>

        {{-- Bagian metode pembayaran hanya untuk kompetisi berbayar --}}
        @if ($isPaid && $setting)

            {{-- Form tambah metode pembayaran --}}
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="mb-5">
                    <h2 class="text-lg font-bold text-slate-800">
                        Tambahkan Metode Pembayaran
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Tambahkan rekening bank, e-wallet, atau QRIS.
                    </p>
                </div>

                <form
                    action="{{ route('admin.payments.methods.store', $competition) }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-5"
                >
                    @csrf

                    {{-- Jenis metode --}}
                    <div>
                        <label for="method_type" class="mb-2 block text-sm font-semibold text-slate-700">
                            Jenis Metode
                        </label>

                        <select
                            name="method_type"
                            id="method_type"
                            required
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                        >
                            <option value="bank" {{ old('method_type') === 'bank' ? 'selected' : '' }}>
                                Transfer Bank
                            </option>

                            <option value="ewallet" {{ old('method_type') === 'ewallet' ? 'selected' : '' }}>
                                E-Wallet
                            </option>

                            <option value="qris" {{ old('method_type') === 'qris' ? 'selected' : '' }}>
                                QRIS
                            </option>
                        </select>
                    </div>

                    {{-- Nama penyedia --}}
                    <div>
                        <label for="provider_name" class="mb-2 block text-sm font-semibold text-slate-700">
                            Nama Penyedia
                        </label>

                        <input
                            type="text"
                            name="provider_name"
                            id="provider_name"
                            value="{{ old('provider_name') }}"
                            required
                            maxlength="100"
                            placeholder="Contoh: BCA, Mandiri, DANA, QRIS Festival Tech"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                        >
                    </div>

                    {{-- Nama pemilik rekening --}}
                    <div id="add-account-name-field">
                        <label for="account_name" class="mb-2 block text-sm font-semibold text-slate-700">
                            Nama Pemilik Rekening
                        </label>

                        <input
                            type="text"
                            name="account_name"
                            id="account_name"
                            value="{{ old('account_name') }}"
                            maxlength="150"
                            placeholder="Contoh: Panitia Festival Teknologi"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                        >
                    </div>

                    {{-- Nomor rekening --}}
                    <div id="add-account-number-field">
                        <label for="account_number" class="mb-2 block text-sm font-semibold text-slate-700">
                            Nomor Rekening / Nomor Tujuan
                        </label>

                        <input
                            type="text"
                            name="account_number"
                            id="account_number"
                            value="{{ old('account_number') }}"
                            maxlength="100"
                            placeholder="Masukkan nomor rekening atau nomor tujuan"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                        >
                    </div>

                    {{-- Upload QRIS --}}
                    <div id="add-qris-field" class="hidden">
                        <label for="image" class="mb-2 block text-sm font-semibold text-slate-700">
                            Gambar QRIS
                        </label>

                        <input
                            type="file"
                            name="image"
                            id="image"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-green-50 file:px-3 file:py-2 file:font-semibold file:text-green-700"
                        >

                        <p class="mt-1 text-xs text-slate-500">
                            Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                        </p>
                    </div>

                    {{-- Urutan tampilan --}}
                    <div>
                        <label for="sort_order" class="mb-2 block text-sm font-semibold text-slate-700">
                            Urutan Tampilan
                        </label>

                        <input
                            type="number"
                            name="sort_order"
                            id="sort_order"
                            min="0"
                            value="{{ old('sort_order', 0) }}"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                        >
                    </div>

                    {{-- Status aktif --}}
                    <div>
                        <label class="flex items-center gap-3">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                {{ old('is_active', '1') ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-slate-300 text-green-600 focus:ring-green-500"
                            >

                            <span class="text-sm font-medium text-slate-700">
                                Aktifkan metode pembayaran ini
                            </span>
                        </label>
                    </div>

                    <div class="flex justify-end border-t border-slate-100 pt-4">
                        <button
                            type="submit"
                            class="rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800"
                        >
                            Tambahkan Metode
                        </button>
                    </div>
                </form>
            </section>

            {{-- Daftar metode pembayaran --}}
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="mb-5">
                    <h2 class="text-lg font-bold text-slate-800">
                        Metode Pembayaran Tersedia
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Kelola rekening dan metode pembayaran kompetisi.
                    </p>
                </div>

                @forelse ($setting->paymentMethods as $method)

                    <div class="mb-5 rounded-xl border border-slate-200 p-4 last:mb-0 sm:p-5">

                        {{-- Informasi metode --}}
                        <div class="mb-5 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="font-bold text-slate-800">
                                        {{ $method->provider_name }}
                                    </h3>

                                    @if ($method->is_active)
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>

                                <p class="mt-1 text-xs uppercase tracking-wide text-slate-500">
                                    {{ $method->method_type === 'bank'
                                        ? 'Transfer Bank'
                                        : ($method->method_type === 'ewallet' ? 'E-Wallet' : 'QRIS') }}
                                </p>
                            </div>
                        </div>

                        {{-- Form edit metode pembayaran --}}
                        <form
                            action="{{ route('admin.payments.methods.update', [$competition, $method]) }}"
                            method="POST"
                            enctype="multipart/form-data"
                            class="space-y-4"
                        >
                            @csrf
                            @method('PUT')

                            {{-- Jenis metode --}}
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">
                                    Jenis Metode
                                </label>

                                <select
                                    name="method_type"
                                    required
                                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                                >
                                    <option value="bank" {{ $method->method_type === 'bank' ? 'selected' : '' }}>
                                        Transfer Bank
                                    </option>

                                    <option value="ewallet" {{ $method->method_type === 'ewallet' ? 'selected' : '' }}>
                                        E-Wallet
                                    </option>

                                    <option value="qris" {{ $method->method_type === 'qris' ? 'selected' : '' }}>
                                        QRIS
                                    </option>
                                </select>
                            </div>

                            {{-- Nama penyedia --}}
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">
                                    Nama Penyedia
                                </label>

                                <input
                                    type="text"
                                    name="provider_name"
                                    value="{{ $method->provider_name }}"
                                    required
                                    maxlength="100"
                                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                                >
                            </div>

                            {{-- Nama pemilik rekening --}}
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">
                                    Nama Pemilik Rekening
                                </label>

                                <input
                                    type="text"
                                    name="account_name"
                                    value="{{ $method->account_name }}"
                                    maxlength="150"
                                    placeholder="Nama pemilik rekening"
                                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                                >
                            </div>

                            {{-- Nomor rekening --}}
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">
                                    Nomor Rekening / Nomor Tujuan
                                </label>

                                <input
                                    type="text"
                                    name="account_number"
                                    value="{{ $method->account_number }}"
                                    maxlength="100"
                                    placeholder="Nomor rekening atau nomor tujuan"
                                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                                >
                            </div>

                            {{-- Gambar QRIS --}}
                            @if ($method->image_path)
                                <div>
                                    <p class="mb-2 text-sm font-semibold text-slate-700">
                                        Gambar QRIS Saat Ini
                                    </p>

                                    <img
                                        src="{{ asset('storage/' . $method->image_path) }}"
                                        alt="QRIS {{ $method->provider_name }}"
                                        class="h-48 w-48 rounded-lg border border-slate-200 bg-white object-contain p-2"
                                    >
                                </div>
                            @endif

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">
                                    Ganti Gambar QRIS (Opsional)
                                </label>

                                <input
                                    type="file"
                                    name="image"
                                    accept=".jpg,.jpeg,.png,.webp"
                                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-green-50 file:px-3 file:py-2 file:font-semibold file:text-green-700"
                                >

                                <p class="mt-1 text-xs text-slate-500">
                                    Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                                </p>
                            </div>

                            {{-- Urutan --}}
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">
                                    Urutan Tampilan
                                </label>

                                <input
                                    type="number"
                                    name="sort_order"
                                    min="0"
                                    value="{{ $method->sort_order }}"
                                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100"
                                >
                            </div>

                            {{-- Status aktif --}}
                            <div>
                                <label class="flex items-center gap-3">
                                    <input
                                        type="checkbox"
                                        name="is_active"
                                        value="1"
                                        {{ $method->is_active ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-slate-300 text-green-600 focus:ring-green-500"
                                    >

                                    <span class="text-sm font-medium text-slate-700">
                                        Aktifkan metode pembayaran
                                    </span>
                                </label>
                            </div>

                            <div class="flex justify-end border-t border-slate-100 pt-4">
                                <button
                                    type="submit"
                                    class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                                >
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>

                        {{-- Form hapus terpisah agar tidak bersarang dengan form edit --}}
                        <form
                            action="{{ route('admin.payments.methods.destroy', [$competition, $method]) }}"
                            method="POST"
                            data-confirm-delete
                            data-confirm-title="Hapus metode pembayaran?"
                            data-confirm-text="Metode {{ $method->provider_name }} akan dihapus secara permanen."
                            class="mt-3 border-t border-slate-100 pt-4"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="rounded-lg border border-red-200 px-5 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50"
                            >
                                Hapus Metode Pembayaran
                            </button>
                        </form>

                    </div>

                @empty
                    <div class="rounded-lg border border-dashed border-slate-300 p-8 text-center">
                        <p class="font-medium text-slate-700">
                            Belum ada metode pembayaran.
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            Tambahkan rekening atau QRIS melalui formulir di atas.
                        </p>
                    </div>
                @endforelse
            </section>

        @elseif (!$isPaid)

            {{-- Informasi jika kompetisi gratis --}}
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-5">
                <h3 class="font-semibold text-blue-800">
                    Kompetisi Gratis
                </h3>

                <p class="mt-1 text-sm text-blue-700">
                    Metode pembayaran tidak ditampilkan kepada peserta karena kompetisi ini tidak memungut biaya pendaftaran.
                </p>
            </div>

        @endif

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Ambil elemen pilihan jenis pembayaran.
            const paymentOptions = document.querySelectorAll(
                'input[name="is_payment_required"]'
            );

            const feeField = document.getElementById('fee-field');
            const descriptionField = document.getElementById('description-field');
            const instructionsField = document.getElementById('instructions-field');

            // Tampilkan atau sembunyikan kolom biaya berdasarkan pilihan.
            function togglePaymentFields() {
                const selected = document.querySelector(
                    'input[name="is_payment_required"]:checked'
                );

                const paid = selected && selected.value === '1';

                feeField?.classList.toggle('hidden', !paid);
                descriptionField?.classList.toggle('hidden', !paid);
                instructionsField?.classList.toggle('hidden', !paid);
            }

            paymentOptions.forEach(function (option) {
                option.addEventListener('change', togglePaymentFields);
            });

            // Atur tampilan formulir tambah metode berdasarkan jenis metode.
            const methodType = document.getElementById('method_type');
            const addAccountName = document.getElementById('add-account-name-field');
            const addAccountNumber = document.getElementById('add-account-number-field');
            const addQris = document.getElementById('add-qris-field');
            const qrisImage = document.getElementById('image');

            function toggleAddMethodFields() {
                if (!methodType) return;

                const isQris = methodType.value === 'qris';

                addAccountName?.classList.toggle('hidden', isQris);
                addAccountNumber?.classList.toggle('hidden', isQris);
                addQris?.classList.toggle('hidden', !isQris);

                // Gambar QRIS wajib diisi ketika menambahkan metode QRIS.
                if (qrisImage) {
                    qrisImage.required = isQris;
                }
            }

            methodType?.addEventListener('change', toggleAddMethodFields);

            // Jalankan sekali ketika halaman pertama kali dibuka.
            togglePaymentFields();
            toggleAddMethodFields();

            // Konfirmasi SweetAlert untuk setiap form penghapusan.
            document.addEventListener('submit', function (event) {
                const form = event.target.closest('form[data-confirm-delete]');

                // Abaikan form yang bukan form penghapusan.
                if (!form) return;

                // Jika sudah dikonfirmasi, biarkan form dikirim.
                if (form.dataset.confirmed === 'true') return;

                // Cegah pengiriman sebelum konfirmasi.
                event.preventDefault();

                // Pastikan SweetAlert tersedia dari package yang dipasang.
                if (typeof Swal === 'undefined') {
                    console.error('SweetAlert tidak ditemukan. Periksa pemuatan package SweetAlert.');
                    return;
                }

                // Tampilkan dialog konfirmasi penghapusan.
                Swal.fire({
                    title: form.dataset.confirmTitle || 'Apakah kamu yakin?',
                    text: form.dataset.confirmText || 'Data ini akan dihapus secara permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then(function (result) {

                    // Kirim form hanya jika admin menyetujui penghapusan.
                    if (result.isConfirmed) {
                        form.dataset.confirmed = 'true';
                        form.requestSubmit();
                    }

                });
            });
        });
    </script>
@endpush
