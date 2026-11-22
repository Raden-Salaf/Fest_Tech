<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionRegistration;
use App\Models\RegistrationSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ParticipantController extends Controller
{
    /**
     * Menampilkan seluruh pendaftaran peserta.
     */
    public function index(Request $request)
    {
        $competitions = Competition::orderBy('name')->get();

        $registrationsQuery = CompetitionRegistration::with([
            'user',
            'competition',
            'members',
            'submissions',
        ])->latest('registered_at');

        if ($request->filled('competition_id')) {
            $registrationsQuery->where(
                'competition_id',
                $request->competition_id
            );
        }

        if ($request->filled('verification_status')) {
            $registrationsQuery->where(
                'verification_status',
                $request->verification_status
            );
        }

        if ($request->filled('search')) {
            $search = trim($request->search);

            $registrationsQuery->where(function ($query) use ($search) {
                $query->where('registration_code', 'like', "%{$search}%")
                    ->orWhere('team_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $registrations = $registrationsQuery
            ->paginate(15)
            ->withQueryString();

        return view('admin.participants.index', compact(
            'competitions',
            'registrations'
        ));
    }

    /**
     * Menampilkan detail pendaftaran peserta.
     */
    public function show(CompetitionRegistration $registration)
    {
        $registration->load([
            'user',
            'competition.paymentSetting',
            'members' => fn ($query) => $query->orderBy('sort_order'),
            'answers.formField',
            'submissions.reviewer',
        ]);

        return view('admin.participants.show', compact('registration'));
    }

    /**
     * Membuka bukti pembayaran dari penyimpanan privat.
     */
    public function proof(RegistrationSubmission $submission)
    {
        abort_unless(
            $submission->payment_proof_path,
            404,
            'Bukti pembayaran tidak tersedia.'
        );

        abort_unless(
            Storage::disk('local')->exists($submission->payment_proof_path),
            404,
            'File bukti pembayaran tidak ditemukan.'
        );

        return Storage::disk('local')->response(
            $submission->payment_proof_path,
            null,
            [
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store',
            ],
            'inline'
        );
    }

    /**
     * Mengambil pengajuan terbaru secara konsisten.
     */
    private function getLatestSubmission(CompetitionRegistration $registration)
    {
        return $registration->submissions()
            ->reorder()
            ->orderByDesc('submission_number')
            ->lockForUpdate()
            ->first();
    }

    /**
     * Menyetujui pendaftaran peserta.
     */
    public function approve(CompetitionRegistration $registration)
    {
        DB::transaction(function () use ($registration) {
            $lockedRegistration = CompetitionRegistration::whereKey(
                $registration->id
            )->lockForUpdate()->firstOrFail();

            if ($lockedRegistration->verification_status === 'approved') {
                throw ValidationException::withMessages([
                    'verification' => 'Pendaftaran ini sudah disetujui.',
                ]);
            }

            $latestSubmission = $this->getLatestSubmission(
                $lockedRegistration
            );

            $paymentSetting = $lockedRegistration->competition
                ->paymentSetting;

            $paymentRequired = $paymentSetting?->is_payment_required ?? false;

            if ($paymentRequired) {
                if (! $latestSubmission || $latestSubmission->status !== 'pending') {
                    throw ValidationException::withMessages([
                        'verification' => 'Tidak ada pengajuan pembayaran yang menunggu pemeriksaan.',
                    ]);
                }

                if (! $latestSubmission->payment_proof_path) {
                    throw ValidationException::withMessages([
                        'verification' => 'Bukti pembayaran belum tersedia.',
                    ]);
                }

                $latestSubmission->update([
                    'status' => 'approved',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                    'rejection_reason' => null,
                ]);
            } else {
                if (! $latestSubmission) {
                    $lockedRegistration->submissions()->create([
                        'submission_number' => 1,
                        'status' => 'approved',
                        'reviewed_by' => auth()->id(),
                        'reviewed_at' => now(),
                    ]);
                } else {
                    if ($latestSubmission->status !== 'pending') {
                        throw ValidationException::withMessages([
                            'verification' => 'Tidak ada pengajuan yang menunggu pemeriksaan.',
                        ]);
                    }

                    $latestSubmission->update([
                        'status' => 'approved',
                        'reviewed_by' => auth()->id(),
                        'reviewed_at' => now(),
                        'rejection_reason' => null,
                    ]);
                }
            }

            $lockedRegistration->update([
                'verification_status' => 'approved',
            ]);
        });

        return redirect()
            ->route('admin.participants.show', $registration)
            ->with('success', 'Pendaftaran peserta berhasil disetujui.');
    }

    /**
     * Menolak pendaftaran peserta dengan alasan wajib.
     */
    public function reject(
        Request $request,
        CompetitionRegistration $registration
    ) {
        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'min:5',
                'max:2000',
            ],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.min' => 'Alasan penolakan minimal 5 karakter.',
            'rejection_reason.max' => 'Alasan penolakan maksimal 2000 karakter.',
        ]);

        DB::transaction(function () use ($registration, $validated) {
            $lockedRegistration = CompetitionRegistration::whereKey(
                $registration->id
            )->lockForUpdate()->firstOrFail();

            if ($lockedRegistration->verification_status === 'approved') {
                throw ValidationException::withMessages([
                    'verification' => 'Pendaftaran yang sudah disetujui tidak dapat ditolak.',
                ]);
            }

            $latestSubmission = $this->getLatestSubmission(
                $lockedRegistration
            );

            if ($latestSubmission && $latestSubmission->status !== 'pending') {
                throw ValidationException::withMessages([
                    'verification' => 'Pengajuan terbaru tidak sedang menunggu pemeriksaan.',
                ]);
            }

            if (! $latestSubmission) {
                $lockedRegistration->submissions()->create([
                    'submission_number' => 1,
                    'status' => 'rejected',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                    'rejection_reason' => $validated['rejection_reason'],
                ]);
            } else {
                $latestSubmission->update([
                    'status' => 'rejected',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                    'rejection_reason' => $validated['rejection_reason'],
                ]);
            }

            $lockedRegistration->update([
                'verification_status' => 'rejected',
            ]);
        });

        return redirect()
            ->route('admin.participants.show', $registration)
            ->with('success', 'Pengajuan berhasil ditolak. Alasan penolakan telah disimpan.');
    }
}
