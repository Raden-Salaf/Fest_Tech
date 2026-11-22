
@extends('layouts.admin')

@section('title', 'Dashboard Admin - Festival Teknologi')

@section('page-heading', 'Dashboard')

@section('content')

{{-- Area konten utama dashboard --}}
<main class="px-5 py-8 md:px-8">
    <div class="mx-auto max-w-7xl">

        {{-- Sambutan administrator --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900 md:text-3xl">
                Selamat datang, {{ auth()->user()->name }}!
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Berikut ringkasan aktivitas Festival Teknologi UNUSIA.
            </p>
        </div>

        {{-- Kartu statistik dashboard --}}
        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-5">

            {{-- Total peserta unik yang sudah mendaftar kompetisi --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Peserta</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">
                    {{ $totalParticipants }}
                </p>
                <p class="mt-2 text-xs text-slate-400">
                    Akun yang sudah mendaftar kompetisi
                </p>
            </div>

            {{-- Total seluruh transaksi pendaftaran kompetisi --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Pendaftaran</p>
                <p class="mt-3 text-3xl font-bold text-blue-600">
                    {{ $totalRegistrations }}
                </p>
                <p class="mt-2 text-xs text-slate-400">
                    Seluruh pendaftaran kompetisi
                </p>
            </div>

            {{-- Total administrator --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Administrator</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">
                    {{ $totalAdmins }}
                </p>
                <p class="mt-2 text-xs text-slate-400">
                    Akun pengelola sistem
                </p>
            </div>

            {{-- Total kompetisi --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Kompetisi</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">
                    {{ $totalCompetitions }}
                </p>
                <p class="mt-2 text-xs text-slate-400">
                    Seluruh kompetisi tersimpan
                </p>
            </div>

            {{-- Kompetisi yang sedang membuka pendaftaran --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Pendaftaran Dibuka</p>
                <p class="mt-3 text-3xl font-bold text-emerald-600">
                    {{ $openCompetitions }}
                </p>
                <p class="mt-2 text-xs text-slate-400">
                    Kompetisi berstatus open dan aktif
                </p>
            </div>

        </div>

        {{-- Area aktivitas sistem --}}
        <section class="mt-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">
                Aktivitas Sistem
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                Bagian ini akan menampilkan aktivitas terbaru setelah modul peserta dan kompetisi selesai dibangun.
            </p>
        </section>

    </div>
</main>

@endsection
