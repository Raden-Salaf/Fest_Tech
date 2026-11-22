<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Judul halaman admin --}}
    <title>@yield('title', 'Admin Dashboard - Festival Teknologi')</title>

    {{-- Memuat CSS dan JavaScript melalui Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-800">

    <div class="min-h-screen md:flex">

        {{-- Sidebar navigasi admin --}}
        @include('partials.admin.sidebar')

        <div class="min-w-0 flex-1">

            {{-- Header bagian admin --}}
            @include('partials.admin.topbar')

            {{-- Konten utama halaman --}}
            @yield('content')

        </div>
    </div>

    {{-- Memuat sistem notifikasi SweetAlert --}}
    @sweetAlert

</body>
</html>
