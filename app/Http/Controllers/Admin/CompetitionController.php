<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class CompetitionController extends Controller
{
    /**
     * Menampilkan seluruh kompetisi.
     */
    public function index(Request $request): View
    {
        $competitions = Competition::query()
            ->withCount('registrations')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('slug', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when($request->filled('active'), function ($query) use ($request) {
                $query->where('is_active', $request->active);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.competitions.index', compact('competitions'));
    }

    /**
     * Menampilkan halaman tambah kompetisi.
     */
    public function create(): View
    {
        return view('admin.competitions.create');
    }

    /**
     * Menyimpan kompetisi baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'type' => ['required', Rule::in(['individual', 'team'])],
            'status' => [
                'required',
                Rule::in(['upcoming', 'open', 'ongoing', 'finished']),
            ],
            'is_active' => ['required', 'boolean'],

            'registration_start' => ['nullable', 'date'],
            'registration_end' => [
                'nullable',
                'date',
                'after_or_equal:registration_start',
            ],
            'competition_start' => ['nullable', 'date'],
            'competition_end' => [
                'nullable',
                'date',
                'after_or_equal:competition_start',
            ],

            'min_team_members' => [
                'nullable',
                'required_if:type,team',
                'integer',
                'min:1',
            ],
            'max_team_members' => [
                'nullable',
                'required_if:type,team',
                'integer',
                'min:1',
                'gte:min_team_members',
            ],

            'quota' => ['nullable', 'integer', 'min:1'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
        ]);

        $thumbnailPath = null;

        try {
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')
                    ->store('competitions', 'public');
            }

            $slug = $this->generateUniqueSlug($validated['name']);

            Competition::create([
                ...$validated,
                'slug' => $slug,
                'thumbnail' => $thumbnailPath,
                'min_team_members' => $validated['type'] === 'team'
                    ? $validated['min_team_members']
                    : null,
                'max_team_members' => $validated['type'] === 'team'
                    ? $validated['max_team_members']
                    : null,
            ]);

            return redirect()
                ->route('admin.competitions.index')
                ->with('success', 'Kompetisi berhasil ditambahkan.');
        } catch (Throwable $e) {
            if ($thumbnailPath) {
                Storage::disk('public')->delete($thumbnailPath);
            }

            report($e);

            return back()
                ->withInput()
                ->with('error', 'Kompetisi gagal ditambahkan.');
        }
    }

    /**
     * Menampilkan detail kompetisi di halaman admin.
     */
    public function show(Competition $competition): View
    {
        $competition->load([
            'formFields',
            'paymentSetting',
        ]);

        $competition->loadCount('registrations');

        return view('admin.competitions.show', compact('competition'));
    }

    /**
     * Menampilkan halaman edit kompetisi.
     */
    public function edit(Competition $competition): View
    {
        return view('admin.competitions.edit', compact('competition'));
    }

    /**
     * Memperbarui data kompetisi.
     */
    public function update(
        Request $request,
        Competition $competition
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'type' => ['required', Rule::in(['individual', 'team'])],
            'status' => [
                'required',
                Rule::in(['upcoming', 'open', 'ongoing', 'finished']),
            ],
            'is_active' => ['required', 'boolean'],

            'registration_start' => ['nullable', 'date'],
            'registration_end' => [
                'nullable',
                'date',
                'after_or_equal:registration_start',
            ],
            'competition_start' => ['nullable', 'date'],
            'competition_end' => [
                'nullable',
                'date',
                'after_or_equal:competition_start',
            ],

            'min_team_members' => [
                'nullable',
                'required_if:type,team',
                'integer',
                'min:1',
            ],
            'max_team_members' => [
                'nullable',
                'required_if:type,team',
                'integer',
                'min:1',
                'gte:min_team_members',
            ],

            'quota' => ['nullable', 'integer', 'min:1'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
        ]);

        $newThumbnailPath = null;
        $oldThumbnailPath = $competition->thumbnail;

        try {
            if ($request->hasFile('thumbnail')) {
                $newThumbnailPath = $request->file('thumbnail')
                    ->store('competitions', 'public');
            }

            DB::transaction(function () use (
                $competition,
                $validated,
                $newThumbnailPath
            ) {
                $competition->update([
                    ...$validated,
                    'slug' => $this->generateUniqueSlug(
                        $validated['name'],
                        $competition->id
                    ),
                    'thumbnail' => $newThumbnailPath
                        ?? $competition->thumbnail,
                    'min_team_members' => $validated['type'] === 'team'
                        ? $validated['min_team_members']
                        : null,
                    'max_team_members' => $validated['type'] === 'team'
                        ? $validated['max_team_members']
                        : null,
                ]);
            });

            if ($newThumbnailPath && $oldThumbnailPath) {
                Storage::disk('public')->delete($oldThumbnailPath);
            }

            return redirect()
                ->route('admin.competitions.index')
                ->with('success', 'Kompetisi berhasil diperbarui.');
        } catch (Throwable $e) {
            if ($newThumbnailPath) {
                Storage::disk('public')->delete($newThumbnailPath);
            }

            report($e);

            return back()
                ->withInput()
                ->with('error', 'Kompetisi gagal diperbarui.');
        }
    }

    /**
     * Menghapus kompetisi menggunakan soft delete.
     */
    public function destroy(Competition $competition): RedirectResponse
    {
        if ($competition->registrations()->exists()) {
            return back()->with(
                'error',
                'Kompetisi tidak dapat dihapus karena sudah memiliki riwayat pendaftaran peserta.'
            );
        }

        try {
            $competition->delete();

            if ($competition->thumbnail) {
                Storage::disk('public')->delete($competition->thumbnail);
            }

            return redirect()
                ->route('admin.competitions.index')
                ->with('success', 'Kompetisi berhasil dihapus.');
        } catch (Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Kompetisi gagal dihapus.'
            );
        }
    }

    /**
     * Membuat slug unik berdasarkan nama kompetisi.
     */
    private function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Competition::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
