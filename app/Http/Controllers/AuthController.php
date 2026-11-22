<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman registrasi.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Memproses pendaftaran akun baru.
     */
    public function register(Request $request)
    {
        // Validasi seluruh input registrasi.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Membuat akun baru dengan role default participant dari database.
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        // Langsung login setelah registrasi berhasil.
        Auth::login($user);

        // Regenerasi session untuk keamanan.
        $request->session()->regenerate();

        // Kembali ke halaman utama dengan notifikasi sukses.
        return redirect()
            ->route('home')
            ->with('success', 'Akun berhasil dibuat. Selamat datang di Festival Teknologi UNUSIA!');
    }

    /**
     * Menampilkan halaman login.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Memproses login pengguna.
     */
    public function login(Request $request)
    {
        // Validasi input login.
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Memeriksa email dan password ke database.
        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password tidak sesuai.',
                ])
                ->with('error', 'Login gagal. Periksa kembali email dan password kamu.')
                ->onlyInput('email');
        }

        // Regenerasi session setelah login berhasil.
        $request->session()->regenerate();

        // Kembali ke halaman tujuan atau Home dengan notifikasi sukses.
        return redirect()
            ->intended(route('home'))
            ->with('success', 'Login berhasil. Selamat datang kembali!');
    }

    /**
     * Memproses logout pengguna.
     */
    public function logout(Request $request)
    {
        // Mengakhiri autentikasi pengguna.
        Auth::logout();

        // Menghapus session lama dan membuat CSRF token baru.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Kembali ke Home dengan notifikasi sukses.
        return redirect()
            ->route('home')
            ->with('success', 'Kamu berhasil logout. Sampai jumpa kembali!');
    }
}
