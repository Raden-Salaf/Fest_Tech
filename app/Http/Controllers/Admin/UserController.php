<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar akun beserta pencarian dan filter role.
     */
    public function index(Request $request)
    {
        $usersQuery = User::query()
            ->select(['id', 'name', 'email', 'role', 'created_at']);

        // Pencarian berdasarkan nama atau email.
        if ($request->filled('search')) {
            $search = $request->input('search');

            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan role yang valid.
        if (in_array($request->input('role'), ['admin', 'participant'], true)) {
            $usersQuery->where('role', $request->input('role'));
        }

        // Pagination tetap mempertahankan filter pencarian.
        $users = $usersQuery->latest()->paginate(15)->withQueryString();

        // Menghitung statistik seluruh akun.
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalParticipants = User::where('role', 'participant')->count();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'totalAdmins',
            'totalParticipants'
        ));
    }

    /**
     * Menampilkan formulir edit akun.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data akun.
     */
    public function update(Request $request, User $user)
    {
        // Validasi input dengan tetap mengecualikan email milik akun ini.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => ['required', Rule::in(['admin', 'participant'])],
        ]);

        // Mencegah admin menurunkan role akunnya sendiri.
        if ($user->is(auth()->user()) && $validated['role'] !== 'admin') {
            return back()
                ->withInput()
                ->with('error', 'Kamu tidak dapat mengubah role akun sendiri.')
                ->withErrors([
                    'role' => 'Kamu tidak dapat mengubah role akun sendiri.',
                ]);
        }

        // Mencegah administrator terakhir kehilangan hak akses.
        if (
            $user->role === 'admin' &&
            $validated['role'] !== 'admin' &&
            User::where('role', 'admin')->count() <= 1
        ) {
            return back()
                ->withInput()
                ->with('error', 'Administrator terakhir tidak dapat diturunkan rolenya.')
                ->withErrors([
                    'role' => 'Administrator terakhir tidak dapat diturunkan rolenya.',
                ]);
        }

        // Memperbarui data akun secara eksplisit.
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->save();

        // Kembali ke daftar akun dengan notifikasi sukses.
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data akun berhasil diperbarui.');
    }

    /**
     * Menghapus akun dengan pemeriksaan keamanan.
     */
    public function destroy(User $user)
    {
        // Admin tidak boleh menghapus akun yang sedang digunakan.
        if ($user->is(auth()->user())) {
            return back()->with(
                'error',
                'Kamu tidak dapat menghapus akun sendiri.'
            );
        }

        // Administrator terakhir tidak boleh dihapus.
        if (
            $user->role === 'admin' &&
            User::where('role', 'admin')->count() <= 1
        ) {
            return back()->with(
                'error',
                'Administrator terakhir tidak dapat dihapus.'
            );
        }

        // Akun dengan riwayat pendaftaran tidak boleh dihapus.
        if ($user->competitionRegistrations()->exists()) {
            return back()->with(
                'error',
                'Akun tidak dapat dihapus karena memiliki riwayat pendaftaran kompetisi.'
            );
        }

        // Hapus akun setelah seluruh pemeriksaan berhasil.
        $user->delete();

        // Kembali ke daftar akun dengan notifikasi sukses.
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun berhasil dihapus.');
    }
}
