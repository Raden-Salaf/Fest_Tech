<?php

namespace App\Http\Controllers;

use App\Models\Competition;

class CompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::where('is_active', true)
            ->latest()
            ->get();

        return view('public.kompetisi.index', compact('competitions'));
    }

    public function show(string $slug)
    {
        $competition = Competition::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $canRegister = $competition->isRegistrationOpen();

        return view('public.kompetisi.show', compact('competition', 'canRegister'));
    }
}
