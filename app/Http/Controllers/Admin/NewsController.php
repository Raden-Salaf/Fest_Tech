<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class NewsController extends Controller
{
    /**
     * Menampilkan daftar berita.
     */
    public function index()
    {
        $news = News::with('author')
            ->latest()
            ->paginate(10);

        return view('admin.news.index', compact('news'));
    }

    /**
     * Menampilkan form tambah berita.
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Menyimpan berita baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'thumbnail' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'category' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
        ]);

        $thumbnail = null;

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail')
                ->store('news', 'public');
        }

        $publishedAt = null;

        if ($validated['status'] === 'published') {
            $publishedAt = $validated['published_at'] ?? now();
        }

        $news = News::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'slug' => $this->generateUniqueSlug($validated['title']),
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'thumbnail' => $thumbnail,
            'category' => $validated['category'] ?? null,
            'status' => $validated['status'],
            'published_at' => $publishedAt,
        ]);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil dibuat.');
    }

    /**
     * Menampilkan detail berita.
     */
    public function show(News $news)
    {
        $news->load('author');

        return view('admin.news.show', compact('news'));
    }

    /**
     * Menampilkan form edit berita.
     */
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Memperbarui berita.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'thumbnail' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'category' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
        ]);

        $thumbnail = $news->thumbnail;

        if ($request->hasFile('thumbnail')) {
            if ($thumbnail) {
                Storage::disk('public')->delete($thumbnail);
            }

            $thumbnail = $request->file('thumbnail')
                ->store('news', 'public');
        }

        $publishedAt = $news->published_at;

        if ($validated['status'] === 'draft') {
            $publishedAt = null;
        } elseif (!$publishedAt || $news->status !== 'published') {
            $publishedAt = $validated['published_at'] ?? now();
        } elseif ($request->filled('published_at')) {
            $publishedAt = $validated['published_at'];
        }

        $news->update([
            'title' => $validated['title'],
            'slug' => $this->generateUniqueSlug(
                $validated['title'],
                $news->id
            ),
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'thumbnail' => $thumbnail,
            'category' => $validated['category'] ?? null,
            'status' => $validated['status'],
            'published_at' => $publishedAt,
        ]);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Menghapus berita secara soft delete.
     */
    public function destroy(News $news)
    {
        $news->delete();

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    /**
     * Membuat slug unik berdasarkan judul berita.
     */
    private function generateUniqueSlug(
        string $title,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($title);

        if ($baseSlug === '') {
            $baseSlug = 'berita';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (
            News::withTrashed()
                ->where('slug', $slug)
                ->when(
                    $ignoreId,
                    fn ($query) => $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
