<?php

namespace App\Http\Controllers;

use App\Models\News;

class FestivalController extends Controller
{
    public function index()
    {
        return view('public.home');
    }

    public function berita()
    {
        $news = News::published()
            ->with('author')
            ->latest('published_at')
            ->paginate(9);

        return view('public.berita', compact('news'));
    }

    public function detailBerita($slug)
    {
        $news = News::published()
            ->with('author')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('public.berita-detail', compact('news'));
    }
}
