@extends('layouts.admin')

@section('title', 'Edit Akun - Festival Teknologi')

@section('content')

<main class="px-5 py-8 md:px-8">
    <div class="mx-auto max-w-4xl">

        {{-- Header halaman edit akun --}}
        <div class="mb-8">
            <a href="{{ route('admin.users.index') }}"
                class="text-sm font-medium text-emerald-700 hover:text-emerald-800">
                &larr; Kembali ke Manajemen Users
            </a>

            <h1 class="mt-4 text-2xl font-bold text-slate-900 md:text-3xl">
                Edit Akun
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Perbarui informasi akun website Festival Teknologi UNUSIA.
            </p>
        </div>

        {{-- Informasi akun yang sedang diedit --}}
        <section class="mb-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Akun yang dipilih
            </p>

            <p class="mt-2 font-semibold text-slate-800">
                {{ $user->name }}
            </p>

            <p class="mt-1 text-sm text-slate-500">
                {{ $user->email }}
            </p>
        </section>

        {{-- Form perubahan data akun --}}
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">

                    {{-- Field nama --}}
                    <div>
                        <label for="name"
                            class="mb-2 block text-sm font-semibold text-slate-700">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            maxlength="255"
                            autocomplete="name"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('name') border-red-500 @enderror"
                        >

                        @error('name')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Field email --}}
                    <div>
                        <label for="email"
                            class="mb-2 block text-sm font-semibold text-slate-700">
                            Alamat Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            maxlength="255"
                            autocomplete="email"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('email') border-red-500 @enderror"
                        >

                        @error('email')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Field role akun --}}
                    <div>
                        <label for="role"
                            class="mb-2 block text-sm font-semibold text-slate-700">
                            Hak Akses Akun
                        </label>

                        <select
                            id="role"
                            name="role"
                            required
                            class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('role') border-red-500 @enderror"
                        >
                            <option value="participant"
                                @selected(old('role', $user->role) === 'participant')>
                                Participant
                            </option>

                            <option value="admin"
                                @selected(old('role', $user->role) === 'admin')>
                                Administrator
                            </option>
                        </select>

                        @error('role')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        <p class="mt-2 text-xs text-slate-400">
                            Administrator memiliki akses ke seluruh fitur pengelolaan website.
                        </p>
                    </div>

                </div>

                {{-- Informasi keamanan perubahan akun --}}
                <div class="mt-6 rounded-lg border border-amber-200 bg-amber-50 p-4">
                    <p class="text-sm font-semibold text-amber-800">
                        Informasi keamanan
                    </p>

                    <p class="mt-1 text-xs leading-relaxed text-amber-700">
                        Password akun tidak akan berubah melalui formulir ini.
                        Perubahan role akan memengaruhi hak akses pengguna.
                    </p>
                </div>

                {{-- Tombol aksi form --}}
                <div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                    <a href="{{ route('admin.users.index') }}"
                        class="rounded-lg border border-slate-300 px-5 py-3 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        Batal
                    </a>

                    <button type="submit"
                        class="rounded-lg bg-emerald-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800">
                        Simpan Perubahan
                    </button>

                </div>

            </form>
        </section>

    </div>
</main>

@endsection
