
@extends('layouts.admin')

@section('title', 'Manajemen Users - Festival Teknologi')

@section('content')

<main class="px-5 py-8 md:px-8">
    <div class="mx-auto max-w-7xl">

        {{-- Header halaman manajemen akun --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900 md:text-3xl">
                Manajemen Users
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Kelola seluruh akun yang terdaftar di website Festival Teknologi UNUSIA.
            </p>
        </div>

        {{-- Ringkasan statistik akun --}}
        <div class="mb-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-3">

            {{-- Total seluruh akun website --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Total Akun
                </p>

                <p class="mt-3 text-3xl font-bold text-slate-900">
                    {{ $totalUsers }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Seluruh akun terdaftar
                </p>
            </div>

            {{-- Total akun administrator --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Administrator
                </p>

                <p class="mt-3 text-3xl font-bold text-blue-600">
                    {{ $totalAdmins }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Akun pengelola website
                </p>
            </div>

            {{-- Total akun participant --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Participant
                </p>

                <p class="mt-3 text-3xl font-bold text-emerald-600">
                    {{ $totalParticipants }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Akun peserta website
                </p>
            </div>

        </div>

        {{-- Form pencarian dan filter akun --}}
        <section class="mb-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <form action="{{ route('admin.users.index') }}" method="GET"
                  class="flex flex-col gap-4 lg:flex-row lg:items-end">

                {{-- Pencarian nama dan email --}}
                <div class="w-full flex-1">
                    <label for="search" class="mb-2 block text-sm font-medium text-slate-700">
                        Cari Akun
                    </label>

                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Masukkan nama atau email..."
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >
                </div>

                {{-- Filter berdasarkan role --}}
                <div class="w-full lg:w-56">
                    <label for="role" class="mb-2 block text-sm font-medium text-slate-700">
                        Filter Role
                    </label>

                    <select
                        name="role"
                        id="role"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >
                        <option value="">Semua Role</option>

                        <option value="admin" @selected(request('role') === 'admin')>
                            Administrator
                        </option>

                        <option value="participant" @selected(request('role') === 'participant')>
                            Participant
                        </option>
                    </select>
                </div>

                {{-- Tombol pencarian dan reset --}}
                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="rounded-lg bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800"
                    >
                        Cari
                    </button>

                    <a
                        href="{{ route('admin.users.index') }}"
                        class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                    >
                        Reset
                    </a>
                </div>

            </form>
        </section>

        {{-- Tabel seluruh akun website --}}
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-900">
                    Daftar Akun Website
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Menampilkan akun administrator dan participant.
                </p>
            </div>

            @if ($users->isEmpty())

                {{-- Kondisi ketika hasil pencarian tidak ditemukan --}}
                <div class="px-5 py-16 text-center">
                    <h3 class="font-semibold text-slate-800">
                        Akun Tidak Ditemukan
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Coba gunakan kata kunci atau filter yang berbeda.
                    </p>
                </div>

            @else

                {{-- Tabel dapat digulir horizontal pada layar kecil --}}
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[750px] text-left text-sm">

                        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-4 font-semibold">No.</th>
                                <th class="px-5 py-4 font-semibold">Nama Akun</th>
                                <th class="px-5 py-4 font-semibold">Email</th>
                                <th class="px-5 py-4 font-semibold">Role</th>
                                <th class="px-5 py-4 font-semibold">Tanggal Bergabung</th>
                                <th class="px-5 py-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            @foreach ($users as $index => $user)

                                <tr class="transition hover:bg-slate-50">

                                    {{-- Nomor urut mengikuti pagination --}}
                                    <td class="px-5 py-4 text-slate-500">
                                        {{ $users->firstItem() + $index }}
                                    </td>

                                    {{-- Nama akun --}}
                                    <td class="px-5 py-4 font-semibold text-slate-800">
                                        {{ $user->name }}
                                    </td>

                                    {{-- Email akun --}}
                                    <td class="px-5 py-4 text-slate-600">
                                        {{ $user->email }}
                                    </td>

                                    {{-- Role akun --}}
                                    <td class="px-5 py-4">
                                        @if ($user->role === 'admin')
                                            <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                                Administrator
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                Participant
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Tanggal akun dibuat --}}
                                    <td class="px-5 py-4 text-slate-600">
                                        {{ $user->created_at?->format('d M Y') ?? '-' }}
                                    </td>

                                    {{-- Tombol aksi untuk mengedit dan menghapus akun --}}
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2">

                                            {{-- Tombol menuju halaman edit akun --}}
                                            <a
                                                href="{{ route('admin.users.edit', $user) }}"
                                                class="inline-flex cursor-pointer rounded-lg border border-emerald-200 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50"
                                            >
                                                Edit
                                            </a>

                                            {{-- Tombol hapus dengan konfirmasi SweetAlert --}}
                                            <a
                                                href="{{ route('admin.users.destroy', $user) }}"
                                                data-confirm-delete
                                                data-confirm-title="Hapus akun {{ $user->name }}?"
                                                data-confirm-text="Akun ini akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan."
                                                data-confirm-button="Ya, hapus"
                                                data-confirm-cancel="Batal"
                                                class="inline-flex cursor-pointer rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                            >
                                                Hapus
                                            </a>

                                        </div>
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>
                    </table>
                </div>

                {{-- Navigasi pagination --}}
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $users->links() }}
                </div>

            @endif

        </section>

    </div>
</main>

@endsection
