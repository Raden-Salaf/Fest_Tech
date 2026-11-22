<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    /**
     * Membuat akun administrator pertama.
     */
    public function run(): void
    {
        // Ambil kredensial admin dari file .env.
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');
        $name = env('ADMIN_NAME', 'Administrator');

        // Hentikan proses jika email atau password belum diatur.
        if (empty($email) || empty($password)) {
            throw new RuntimeException(
                'ADMIN_EMAIL dan ADMIN_PASSWORD harus diatur di file .env.'
            );
        }

        // Cari akun berdasarkan email.
        // Jika belum ada, Laravel akan membuat akun baru.
        $admin = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
            ]
        );

        // Atur role secara eksplisit karena role tidak termasuk Fillable.
        // Ini mencegah pengguna biasa mengubah role melalui mass assignment.
        $admin->forceFill([
            'role' => 'admin',
        ])->save();
    }
}
