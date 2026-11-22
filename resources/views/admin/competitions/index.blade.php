@extends('layouts.admin')

@section('title', 'Manajemen Kompetisi - Festival Teknologi')

@section('content')

<main class="px-5 py-8 md:px-8">
    <div class="mx-auto max-w-7xl">

        {{-- Header halaman --}}
        <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 md:text-3xl">
                    Manajemen Kompetisi
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Kelola informasi, jadwal, kuota, dan status seluruh kompetisi.
                </p>
            </div>

            <a
                href="{{ route('admin.competitions.create') }}"
                class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg bg-emerald-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800"
            >
                <span class="text-lg leading-none">+</span>
                Tambah Kompetisi
            </a>
        </div>

        {{-- Ringkasan kompetisi --}}
        <div class="mb-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Total Kompetisi
                </p>

                <p class="mt-3 text-3xl font-bold text-slate-900">
                    {{ $competitions->total() }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Kompetisi dalam database
                </p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Sedang Ditampilkan
                </p>

                <p class="mt-3 text-3xl font-bold text-blue-600">
                    {{ $competitions->count() }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Data pada halaman ini
                </p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Kompetisi Aktif
                </p>

                <p class="mt-3 text-3xl font-bold text-emerald-600">
                    {{ $competitions->getCollection()->where('is_active', true)->count() }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Pada halaman ini
                </p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Kompetisi Nonaktif
                </p>

                <p class="mt-3 text-3xl font-bold text-red-600">
                    {{ $competitions->getCollection()->where('is_active', false)->count() }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Pada halaman ini
                </p>
            </div>

        </div>

        {{-- Pencarian dan filter --}}
        <section class="mb-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <form
                action="{{ route('admin.competitions.index') }}"
                method="GET"
                class="flex flex-col gap-4 lg:flex-row lg:items-end"
            >

                {{-- Pencarian --}}
                <div class="w-full flex-1">
                    <label for="search" class="mb-2 block text-sm font-medium text-slate-700">
                        Cari Kompetisi
                    </label>

                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Masukkan nama atau slug kompetisi..."
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >
                </div>

                {{-- Filter status --}}
                <div class="w-full lg:w-44">
                    <label for="status" class="mb-2 block text-sm font-medium text-slate-700">
                        Status Kompetisi
                    </label>

                    <select
                        name="status"
                        id="status"
                        class="w-full cursor-pointer rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >
                        <option value="">Semua Status</option>
                        <option value="upcoming" @selected(request('status') === 'upcoming')>
                            Akan Datang
                        </option>
                        <option value="open" @selected(request('status') === 'open')>
                            Dibuka
                        </option>
                        <option value="ongoing" @selected(request('status') === 'ongoing')>
                            Berlangsung
                        </option>
                        <option value="finished" @selected(request('status') === 'finished')>
                            Selesai
                        </option>
                    </select>
                </div>

                {{-- Filter jenis --}}
                <div class="w-full lg:w-40">
                    <label for="type" class="mb-2 block text-sm font-medium text-slate-700">
                        Jenis Kompetisi
                    </label>

                    <select
                        name="type"
                        id="type"
                        class="w-full cursor-pointer rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >
                        <option value="">Semua Jenis</option>
                        <option value="individual" @selected(request('type') === 'individual')>
                            Individu
                        </option>
                        <option value="team" @selected(request('type') === 'team')>
                            Tim
                        </option>
                    </select>
                </div>

                {{-- Filter aktif --}}
                <div class="w-full lg:w-40">
                    <label for="active" class="mb-2 block text-sm font-medium text-slate-700">
                        Status Aktivasi
                    </label>

                    <select
                        name="active"
                        id="active"
                        class="w-full cursor-pointer rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >
                        <option value="">Semua</option>
                        <option value="1" @selected(request('active') === '1')>
                            Aktif
                        </option>
                        <option value="0" @selected(request('active') === '0')>
                            Nonaktif
                        </option>
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="cursor-pointer rounded-lg bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800"
                    >
                        Cari
                    </button>

                    <a
                        href="{{ route('admin.competitions.index') }}"
                        class="cursor-pointer rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                    >
                        Reset
                    </a>
                </div>

            </form>
        </section>

        {{-- Tabel kompetisi --}}
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-900">
                    Daftar Kompetisi
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Informasi kompetisi yang dikelola oleh administrator.
                </p>
            </div>

            @if ($competitions->isEmpty())

                <div class="px-5 py-16 text-center">
                    <h3 class="font-semibold text-slate-800">
                        Kompetisi Tidak Ditemukan
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Belum ada kompetisi yang sesuai dengan pencarian atau filter.
                    </p>
                </div>

            @else

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[950px] text-left text-sm">

                        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-4 font-semibold">No.</th>
                                <th class="px-5 py-4 font-semibold">Kompetisi</th>
                                <th class="px-5 py-4 font-semibold">Jenis</th>
                                <th class="px-5 py-4 font-semibold">Status</th>
                                <th class="px-5 py-4 font-semibold">Aktivasi</th>
                                <th class="px-5 py-4 font-semibold">Pendaftar</th>
                                <th class="px-5 py-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            @foreach ($competitions as $index => $competition)

                                <tr class="transition hover:bg-slate-50">

                                    <td class="px-5 py-4 text-slate-500">
                                        {{ $competitions->firstItem() + $index }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-slate-800">
                                            {{ $competition->name }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            {{ $competition->slug }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4">
                                        @if ($competition->type === 'team')
                                            <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                                Tim
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700">
                                                Individu
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        @php
                                            $statusLabels = [
                                                'upcoming' => ['Akan Datang', 'bg-amber-100 text-amber-700'],
                                                'open' => ['Dibuka', 'bg-emerald-100 text-emerald-700'],
                                                'ongoing' => ['Berlangsung', 'bg-blue-100 text-blue-700'],
                                                'finished' => ['Selesai', 'bg-slate-100 text-slate-600'],
                                            ];

                                            $status = $statusLabels[$competition->status] ?? [
                                                ucfirst($competition->status),
                                                'bg-slate-100 text-slate-600'
                                            ];
                                        @endphp

                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $status[1] }}">
                                            {{ $status[0] }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        @if ($competition->is_active)
                                            <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="font-semibold text-slate-800">
                                            {{ $competition->registrations_count }}
                                        </span>

                                        <span class="text-xs text-slate-400">
                                            pendaftar
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2">

                                            <a
                                                href="{{ route('admin.competitions.show', $competition) }}"
                                                class="inline-flex cursor-pointer rounded-lg border border-blue-200 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-50"
                                            >
                                                Detail
                                            </a>

                                            <a
                                                href="{{ route('admin.competitions.edit', $competition) }}"
                                                class="inline-flex cursor-pointer rounded-lg border border-emerald-200 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50"
                                            >
                                                Edit
                                            </a>

                                            <form
                                                action="{{ route('admin.competitions.destroy', $competition) }}"
                                                method="POST"
                                                data-confirm-delete
                                                data-confirm-title="Hapus kompetisi {{ $competition->name }}?"
                                                data-confirm-text="Kompetisi ini hanya dapat dihapus jika belum memiliki riwayat pendaftaran."
                                                data-confirm-button="Ya, hapus"
                                                data-confirm-cancel="Batal"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex cursor-pointer rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                                >
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

                {{-- Pagination --}}
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $competitions->links() }}
                </div>

            @endif

        </section>

    </div>
</main>

@endsection
