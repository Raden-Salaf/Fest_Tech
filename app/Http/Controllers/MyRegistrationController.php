<?php

namespace App\Http\Controllers;

use App\Models\CompetitionRegistration;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MyRegistrationController extends Controller
{
    /**
     * Menampilkan seluruh pendaftaran milik peserta yang sedang login.
     */
    public function index(Request $request): View
    {
        $registrations = CompetitionRegistration::query()
            ->with([
                'competition',
                'members',
                'submissions',
            ])
            ->where('user_id', $request->user()->id)
            ->latest('registered_at')
            ->paginate(10);

        return view('public.pendaftaran.index', compact('registrations'));
    }
}
