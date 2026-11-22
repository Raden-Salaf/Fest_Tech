@extends('layouts.admin')

@section('title', 'Peserta Kompetisi - Festival Teknologi')

@section('content')
<main class="px-5 py-8 md:px-8">
    <div class="mx-auto max-w-7xl">

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900 md:text-3xl">
                Peserta Kompetisi
            </h1>
            <p class="mt-2 text-sm text-slate-500">
                Kelola pendaftaran dan verifikasi peserta Festival Teknologi UNUSIA.
            </p>
        </div>

        <div class="mb-6 grid gap-4 sm:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Pendaftaran</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">
                    {{ $registrations->total() }}
                </p>
            </div>

            <div class="rounded-xl border border-amber-200 bg-amber-50 p-5">
                <p class="text-sm font-medium text-amber-700">
                    Menunggu Verifikasi
                </p>
                <p class="mt-2 text-3xl font-bold text-amber-800">
                    {{ \App\Models\CompetitionRegistration::where('verification_status', 'pending')->count() }}
                </p>
            </div>
        </div>

        <section class="mb-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <form action="{{ route('admin.participants.index') }}"
                method="GET"
                class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">

                <div>
                    <label for="search" class="mb-2 block text-sm font-medium text-slate-700">
                        Cari Peserta
                    </label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Nama, email, kode pendaftaran..."
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >
                </div>

                <div>
                    <label for="competition_id" class="mb-2 block text-sm font-medium text-slate-700">
                        Kompetisi
                    </label>
                    <select name="competition_id" id="competition_id"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-emerald-600">
                        <option value="">Semua Kompetisi</option>
                        @foreach ($competitions as $competition)
                            <option value="{{ $competition->id }}"
                                @selected(request('competition_id') == $competition->id)>
                                {{ $competition->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="verification_status" class="mb-2 block text-sm font-medium text-slate-700">
                        Status Verifikasi
                    </label>
                    <select name="verification_status" id="verification_status"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-emerald-600">
                        <option value="">Semua Status</option>
                        <option value="pending" @selected(request('verification_status') === 'pending')>Menunggu</option>
                        <option value="approved" @selected(request('verification_status') === 'approved')>Disetujui</option>
                        <option value="rejected" @selected(request('verification_status') === 'rejected')>Ditolak</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 cursor-pointer rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">
                        Terapkan
                    </button>

                    <a href="{{ route('admin.participants.index') }}"
                        class="cursor-pointer rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-900">Daftar Pendaftaran</h2>
                <p class="mt-1 text-xs text-slate-500">
                    Pilih detail untuk memeriksa data dan dokumen peserta.
                </p>
            </div>

            @if ($registrations->isEmpty())
                <div class="px-5 py-16 text-center">
                    <h3 class="font-semibold text-slate-800">Belum Ada Pendaftaran</h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Tidak ditemukan data pendaftaran sesuai filter.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1000px] text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-4">No.</th>
                                <th class="px-5 py-4">Peserta</th>
                                <th class="px-5 py-4">Kompetisi</th>
                                <th class="px-5 py-4">Tim</th>
                                <th class="px-5 py-4">Tanggal</th>
                                <th class="px-5 py-4">Verifikasi</th>
                                <th class="px-5 py-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach ($registrations as $index => $registration)
                                @php
                                    $verificationClasses = [
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'approved' => 'bg-emerald-100 text-emerald-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                    ];

                                    $verificationLabels = [
                                        'pending' => 'Menunggu',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                    ];
                                @endphp

                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-5 py-4 text-slate-500">
                                        {{ $registrations->firstItem() + $index }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-slate-800">
                                            {{ $registration->user->name }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $registration->user->email }}
                                        </p>
                                        <p class="mt-1 text-xs font-medium text-emerald-700">
                                            {{ $registration->registration_code }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4 font-medium text-slate-700">
                                        {{ $registration->competition->name }}
                                    </td>

                                    <td class="px-5 py-4">
                                        @if ($registration->team_name)
                                            <p class="font-semibold text-slate-700">
                                                {{ $registration->team_name }}
                                            </p>
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $registration->members->count() + 1 }} anggota
                                            </p>
                                        @else
                                            <span class="text-slate-500">Individu</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-slate-600">
                                        {{ $registration->registered_at?->format('d M Y H:i') ?? '-' }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $verificationClasses[$registration->verification_status] ?? 'bg-slate-100 text-slate-600' }}">
                                            {{ $verificationLabels[$registration->verification_status] ?? ucfirst($registration->verification_status) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <a href="{{ route('admin.participants.show', $registration) }}"
                                            class="inline-flex cursor-pointer items-center rounded-lg bg-emerald-700 px-4 py-2 text-xs font-semibold text-white transition hover:bg-emerald-800">
                                            Periksa Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $registrations->links() }}
                </div>
            @endif
        </section>
    </div>
</main>
@endsection

