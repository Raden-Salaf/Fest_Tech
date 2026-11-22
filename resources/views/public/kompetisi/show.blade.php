@extends('layouts.app')

@section('title', $competition->name . ' - Festival Teknologi UNUSIA')

@section('content')

    <div class="mx-auto max-w-5xl px-6 py-12">

        <a href="{{ route('kompetisi.index') }}">
            &larr; Kembali ke daftar kompetisi
        </a>

        <h1 class="mt-8 text-3xl font-bold">
            {{ $competition->name }}
        </h1>

        <p class="mt-4">
            {{ $competition->description }}
        </p>

        <hr class="my-8">

        <h2 class="text-xl font-bold">Informasi Kompetisi</h2>

        <table class="mt-4 w-full border-collapse text-left">
            <tbody>
                <tr>
                    <th class="border p-3">Jenis</th>
                    <td class="border p-3">
                        {{ $competition->type === 'team' ? 'Tim' : 'Individu' }}
                    </td>
                </tr>

                <tr>
                    <th class="border p-3">Status</th>
                    <td class="border p-3">
                        {{ $competition->status }}
                    </td>
                </tr>

                <tr>
                    <th class="border p-3">Kuota</th>
                    <td class="border p-3">
                        {{ $competition->quota ?? 'Tidak dibatasi' }}
                    </td>
                </tr>

                <tr>
                    <th class="border p-3">Minimal Anggota Tim</th>
                    <td class="border p-3">
                        {{ $competition->min_team_members ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <th class="border p-3">Maksimal Anggota Tim</th>
                    <td class="border p-3">
                        {{ $competition->max_team_members ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <th class="border p-3">Pendaftaran Dimulai</th>
                    <td class="border p-3">
                        {{ $competition->registration_start?->format('d F Y H:i') ?? 'Belum ditentukan' }}
                    </td>
                </tr>

                <tr>
                    <th class="border p-3">Pendaftaran Berakhir</th>
                    <td class="border p-3">
                        {{ $competition->registration_end?->format('d F Y H:i') ?? 'Belum ditentukan' }}
                    </td>
                </tr>

                <tr>
                    <th class="border p-3">Pelaksanaan Dimulai</th>
                    <td class="border p-3">
                        {{ $competition->competition_start?->format('d F Y H:i') ?? 'Belum ditentukan' }}
                    </td>
                </tr>

                <tr>
                    <th class="border p-3">Pelaksanaan Berakhir</th>
                    <td class="border p-3">
                        {{ $competition->competition_end?->format('d F Y H:i') ?? 'Belum ditentukan' }}
                    </td>
                </tr>
            </tbody>
        </table>

        <hr class="my-8">

        @if ($canRegister)
            <p>Pendaftaran kompetisi sedang dibuka.</p>
        @else
            <p>Pendaftaran kompetisi belum tersedia.</p>
        @endif

    </div>

@endsection
