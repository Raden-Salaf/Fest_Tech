<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionRegistration;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard administrator.
     */
    public function index()
    {
        // Menghitung jumlah akun unik yang benar-benar pernah mendaftar kompetisi.
        $totalParticipants = CompetitionRegistration::distinct('user_id')
            ->count('user_id');

        // Menghitung seluruh akun administrator.
        $totalAdmins = User::where('role', 'admin')->count();

        // Menghitung seluruh kompetisi yang tersimpan.
        $totalCompetitions = Competition::count();

        // Menghitung kompetisi aktif yang sedang membuka pendaftaran.
        $openCompetitions = Competition::where('status', 'open')
            ->where('is_active', true)
            ->count();

        // Menghitung seluruh transaksi pendaftaran, termasuk akun yang mengikuti beberapa kompetisi.
        $totalRegistrations = CompetitionRegistration::count();

        // Mengirimkan seluruh statistik ke halaman dashboard.
        return view('admin.dashboard', compact(
            'totalParticipants',
            'totalAdmins',
            'totalCompetitions',
            'openCompetitions',
            'totalRegistrations'
        ));
    }
}
