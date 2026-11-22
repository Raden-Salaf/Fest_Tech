<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Judul halaman dapat diatur dari masing-masing Blade --}}
    <title>@yield('title', 'Festival Teknologi UNUSIA')</title>

    {{-- Memuat CSS dan JavaScript melalui Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    {{-- Navbar utama website --}}
    @include('partials.navbar')

    {{-- Konten utama halaman --}}
    @yield('content')

    {{-- Footer utama website --}}
    @include('partials.footer')

    {{-- Menampilkan notifikasi SweetAlert dari flash session Laravel --}}
    @sweetAlert

</body>
</html>
