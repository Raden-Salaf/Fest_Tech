<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionRegistration;
use App\Models\RegistrationSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class CompetitionRegistrationController extends Controller
{
    /**
     * Menampilkan formulir pendaftaran kompetisi.
     */
    public function create(string $slug)
    {
        $competition = Competition::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        if (! $competition->isRegistrationOpen()) {
            return redirect()
                ->route('kompetisi.show', $competition->slug)
                ->with('error', 'Pendaftaran kompetisi belum dibuka atau sudah ditutup.');
        }

        $alreadyRegistered = CompetitionRegistration::query()
            ->where('user_id', auth()->id())
            ->where('competition_id', $competition->id)
            ->exists();

        if ($alreadyRegistered) {
            return redirect()
                ->route('kompetisi.show', $competition->slug)
                ->with('error', 'Kamu sudah terdaftar pada kompetisi ini.');
        }

        $totalRegistrations = CompetitionRegistration::query()
            ->where('competition_id', $competition->id)
            ->count();

        if (
            $competition->quota !== null
            && $totalRegistrations >= $competition->quota
        ) {
            return redirect()
                ->route('kompetisi.show', $competition->slug)
                ->with('error', 'Mohon maaf, kuota kompetisi sudah penuh.');
        }

        $formFields = $competition->formFields()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $paymentSetting = $competition->paymentSetting()
            ->with([
                'paymentMethods' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('sort_order');
                },
            ])
            ->first();

        $requiresPayment = (bool) (
            $paymentSetting?->is_payment_required ?? false
        );

        if (
            $requiresPayment
            && (! $paymentSetting || $paymentSetting->paymentMethods->isEmpty())
        ) {
            return redirect()
                ->route('kompetisi.show', $competition->slug)
                ->with(
                    'error',
                    'Metode pembayaran belum tersedia. Silakan hubungi panitia.'
                );
        }

        return view('public.kompetisi.register', compact(
            'competition',
            'formFields',
            'paymentSetting',
            'requiresPayment'
        ));
    }

    /**
     * Memproses pendaftaran kompetisi pertama kali.
     */
    public function store(Request $request, string $slug)
    {
        $storedProofPath = null;

        try {
            $competition = Competition::query()
                ->where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail();

            if (! $competition->isRegistrationOpen()) {
                return redirect()
                    ->route('kompetisi.show', $competition->slug)
                    ->with('error', 'Pendaftaran kompetisi sudah ditutup.');
            }

            $alreadyRegistered = CompetitionRegistration::query()
                ->where('user_id', auth()->id())
                ->where('competition_id', $competition->id)
                ->exists();

            if ($alreadyRegistered) {
                return redirect()
                    ->route('kompetisi.show', $competition->slug)
                    ->with('error', 'Kamu sudah terdaftar pada kompetisi ini.');
            }

            $paymentSetting = $competition->paymentSetting()
                ->with([
                    'paymentMethods' => function ($query) {
                        $query->where('is_active', true)
                            ->orderBy('sort_order');
                    },
                ])
                ->first();

            $requiresPayment = (bool) (
                $paymentSetting?->is_payment_required ?? false
            );

            if (
                $requiresPayment
                && (! $paymentSetting || $paymentSetting->paymentMethods->isEmpty())
            ) {
                return back()
                    ->withInput()
                    ->with('error', 'Metode pembayaran belum tersedia.');
            }

            $formFields = $competition->formFields()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            $rules = [
                'answers' => ['sometimes', 'array'],
                'payment_description' => ['nullable', 'string', 'max:2000'],
            ];

            $messages = [
                'team_name.required' => 'Nama tim wajib diisi.',
                'team_name.string' => 'Nama tim harus berupa teks.',
                'team_name.max' => 'Nama tim maksimal 255 karakter.',

                'members.required' => 'Anggota tim wajib diisi.',
                'members.array' => 'Format anggota tim tidak valid.',
                'members.min' => 'Jumlah anggota tim belum memenuhi batas minimum.',
                'members.max' => 'Jumlah anggota tim melebihi batas maksimum.',
                'members.*.required' => 'Nama anggota tim wajib diisi.',
                'members.*.string' => 'Nama anggota harus berupa teks.',
                'members.*.max' => 'Nama anggota maksimal 255 karakter.',

                'payment_proof.required' => 'Bukti pembayaran wajib diunggah.',
                'payment_proof.file' => 'Bukti pembayaran harus berupa file.',
                'payment_proof.mimes' => 'Format bukti pembayaran harus JPG, JPEG, PNG, WEBP, atau PDF.',
                'payment_proof.max' => 'Ukuran bukti pembayaran maksimal 5 MB.',

                'payment_description.string' => 'Keterangan pembayaran harus berupa teks.',
                'payment_description.max' => 'Keterangan pembayaran maksimal 2000 karakter.',
            ];

            $attributes = [
                'team_name' => 'nama tim',
                'members' => 'anggota tim',
                'payment_proof' => 'bukti pembayaran',
                'payment_description' => 'keterangan pembayaran',
            ];

            if ($competition->type === 'team') {
                $minimumAdditionalMembers = max(
                    0,
                    (int) $competition->min_team_members - 1
                );

                $maximumAdditionalMembers = max(
                    0,
                    (int) $competition->max_team_members - 1
                );

                $rules['team_name'] = [
                    'required',
                    'string',
                    'max:255',
                ];

                $rules['members'] = [
                    'required',
                    'array',
                    'min:' . $minimumAdditionalMembers,
                    'max:' . $maximumAdditionalMembers,
                ];

                $rules['members.*'] = [
                    'required',
                    'string',
                    'max:255',
                ];
            } else {
                $rules['team_name'] = ['nullable', 'prohibited'];
                $rules['members'] = ['nullable', 'prohibited'];
            }

            $allowedFieldIds = $formFields
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->all();

            foreach ($formFields as $field) {
                $fieldName = 'answers.' . $field->id;

                $fieldOptions = is_array($field->options)
                    ? $field->options
                    : [];

                $requiredRule = $field->is_required
                    ? 'required'
                    : 'nullable';

                $fieldRules = [$requiredRule];

                switch ($field->type) {
                    case 'text':
                    case 'textarea':
                    case 'tel':
                        $fieldRules[] = 'string';
                        $fieldRules[] = 'max:10000';
                        break;

                    case 'email':
                        $fieldRules[] = 'email';
                        $fieldRules[] = 'max:255';
                        break;

                    case 'number':
                        $fieldRules[] = 'numeric';
                        break;

                    case 'url':
                        $fieldRules[] = 'url';
                        $fieldRules[] = 'max:2048';
                        break;

                    case 'date':
                        $fieldRules[] = 'date';
                        break;

                    case 'select':
                    case 'radio':
                        $fieldRules[] = 'string';
                        $fieldRules[] = Rule::in($fieldOptions);
                        break;

                    case 'checkbox':
                        $fieldRules[] = 'array';

                        if ($field->is_required) {
                            $fieldRules[] = 'min:1';
                        }

                        $rules[$fieldName . '.*'] = [
                            'required',
                            'string',
                            Rule::in($fieldOptions),
                        ];
                        break;

                    default:
                        $fieldRules[] = 'string';
                        $fieldRules[] = 'max:10000';
                        break;
                }

                $rules[$fieldName] = $fieldRules;

                $attributes[$fieldName] = $field->label;
                $attributes[$fieldName . '.*'] = $field->label;
            }

            $submittedAnswers = $request->input('answers', []);

            if (! is_array($submittedAnswers)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'answers' => 'Format jawaban formulir tidak valid.',
                    ]);
            }

            $unexpectedFieldIds = array_diff(
                array_map('strval', array_keys($submittedAnswers)),
                $allowedFieldIds
            );

            if (count($unexpectedFieldIds) > 0) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'answers' => 'Terdapat jawaban formulir yang tidak valid.',
                    ]);
            }

            if ($requiresPayment) {
                $rules['payment_proof'] = [
                    'required',
                    'file',
                    'mimes:jpg,jpeg,png,webp,pdf',
                    'max:5120',
                ];
            } else {
                $rules['payment_proof'] = ['prohibited'];
                $rules['payment_description'] = ['prohibited'];
            }

            $validator = Validator::make(
                $request->all(),
                $rules,
                $messages,
                $attributes
            );

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Periksa kembali data pendaftaran kamu.');
            }

            $validated = $validator->validated();

            if ($requiresPayment) {
                $storedProofPath = $request->file('payment_proof')
                    ->store('registration-proofs');
            }

            $registration = DB::transaction(function () use (
                $competition,
                $validated,
                $formFields,
                $requiresPayment,
                $storedProofPath
            ) {
                $lockedCompetition = Competition::query()
                    ->whereKey($competition->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $lockedCompetition->isRegistrationOpen()) {
                    throw new \RuntimeException(
                        'Pendaftaran kompetisi sudah ditutup.'
                    );
                }

                $alreadyRegistered = CompetitionRegistration::query()
                    ->where('user_id', auth()->id())
                    ->where('competition_id', $lockedCompetition->id)
                    ->exists();

                if ($alreadyRegistered) {
                    throw new \RuntimeException(
                        'Kamu sudah terdaftar pada kompetisi ini.'
                    );
                }

                $totalRegistrations = CompetitionRegistration::query()
                    ->where('competition_id', $lockedCompetition->id)
                    ->count();

                if (
                    $lockedCompetition->quota !== null
                    && $totalRegistrations >= $lockedCompetition->quota
                ) {
                    throw new \RuntimeException(
                        'Mohon maaf, kuota kompetisi sudah penuh.'
                    );
                }

                $registration = CompetitionRegistration::create([
                    'user_id' => auth()->id(),
                    'competition_id' => $lockedCompetition->id,
                    'team_name' => $lockedCompetition->type === 'team'
                        ? $validated['team_name']
                        : null,
                    'status' => 'pending',
                    'verification_status' => 'pending',
                    'registered_at' => now(),
                ]);

                if ($lockedCompetition->type === 'team') {
                    foreach ($validated['members'] as $index => $memberName) {
                        $registration->members()->create([
                            'name' => $memberName,
                            'sort_order' => $index + 1,
                        ]);
                    }
                }

                $answers = $validated['answers'] ?? [];

                foreach ($formFields as $field) {
                    if (! array_key_exists($field->id, $answers)) {
                        continue;
                    }

                    $answer = $answers[$field->id];

                    if ($field->type === 'checkbox') {
                        $answer = json_encode(
                            $answer,
                            JSON_UNESCAPED_UNICODE
                        );
                    }

                    $registration->answers()->create([
                        'form_field_id' => $field->id,
                        'answer' => $answer,
                    ]);
                }

                if ($requiresPayment) {
                    RegistrationSubmission::create([
                        'registration_id' => $registration->id,
                        'submission_number' => 1,
                        'payment_proof_path' => $storedProofPath,
                        'payment_description' => $validated['payment_description'] ?? null,
                        'appeal_message' => null,
                        'status' => 'pending',
                    ]);
                }

                return $registration;
            });

            return redirect()
                ->route('pendaftaran.status', $registration)
                ->with(
                    'success',
                    'Pendaftaran berhasil dikirim. Kode registrasi kamu: '
                    . $registration->registration_code
                    . '. Silakan tunggu pemeriksaan panitia.'
                );

        } catch (Throwable $exception) {
            if ($storedProofPath) {
                Storage::delete($storedProofPath);
            }

            report($exception);

            $businessErrors = [
                'Pendaftaran kompetisi sudah ditutup.',
                'Kamu sudah terdaftar pada kompetisi ini.',
                'Mohon maaf, kuota kompetisi sudah penuh.',
            ];

            if (in_array($exception->getMessage(), $businessErrors, true)) {
                return back()
                    ->withInput()
                    ->with('error', $exception->getMessage());
            }

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat menyimpan pendaftaran. Silakan coba kembali.'
                );
        }
    }

    /**
     * Menampilkan status dan riwayat pendaftaran milik peserta.
     */
    public function showStatus(CompetitionRegistration $registration)
    {
        // Peserta hanya boleh melihat pendaftaran miliknya sendiri.
        abort_unless(
            (int) $registration->user_id === (int) auth()->id(),
            403
        );

        $registration->load([
            'competition.paymentSetting',
            'members',
            'submissions.reviewer',
        ]);

        $latestSubmission = $registration->submissions->last();

        return view('public.kompetisi.status', compact(
            'registration',
            'latestSubmission'
        ));
    }

    /**
     * Memproses pengajuan ulang setelah pengajuan sebelumnya ditolak.
     */
    public function resubmit(Request $request, CompetitionRegistration $registration)
    {
        // Pastikan pendaftaran memang milik peserta yang sedang login.
        abort_unless(
            (int) $registration->user_id === (int) auth()->id(),
            403
        );

        $registration->load('competition.paymentSetting');

        $paymentSetting = $registration->competition->paymentSetting;

        $requiresPayment = (bool) (
            $paymentSetting?->is_payment_required ?? false
        );

        // Ambil pengajuan terbaru dengan menghapus urutan bawaan relasi.
        $latestSubmission = $registration->submissions()
            ->reorder()
            ->orderByDesc('submission_number')
            ->first();

        // Hanya pengajuan yang ditolak yang boleh diajukan ulang.
        if (
            $registration->verification_status !== 'rejected'
            || ! $latestSubmission
            || $latestSubmission->status !== 'rejected'
        ) {
            return redirect()
                ->route('pendaftaran.status', $registration)
                ->with('error', 'Pengajuan ini belum dapat diajukan ulang.');
        }

        $rules = [
            'appeal_message' => [
                $requiresPayment ? 'nullable' : 'required',
                'string',
                'min:5',
                'max:2000',
            ],
        ];

        $messages = [
            'appeal_message.required' => 'Pesan pengajuan ulang wajib diisi.',
            'appeal_message.min' => 'Pesan pengajuan ulang minimal 5 karakter.',
            'appeal_message.max' => 'Pesan pengajuan ulang maksimal 2000 karakter.',
            'payment_description.max' => 'Keterangan pembayaran maksimal 2000 karakter.',
            'payment_proof.required' => 'Bukti pembayaran baru wajib diunggah.',
            'payment_proof.mimes' => 'Format bukti pembayaran harus JPG, JPEG, PNG, WEBP, atau PDF.',
            'payment_proof.max' => 'Ukuran bukti pembayaran maksimal 5 MB.',
        ];

        if ($requiresPayment) {
            $rules['payment_proof'] = [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:5120',
            ];

            $rules['payment_description'] = [
                'nullable',
                'string',
                'max:2000',
            ];
        } else {
            $rules['payment_proof'] = ['prohibited'];
            $rules['payment_description'] = ['prohibited'];
        }

        $validated = $request->validate(
            $rules,
            $messages
        );

        $storedProofPath = null;

        try {
            // Simpan bukti baru tanpa menghapus bukti pengajuan sebelumnya.
            if ($requiresPayment) {
                $storedProofPath = $request->file('payment_proof')
                    ->store('registration-proofs');
            }

            DB::transaction(function () use (
                $registration,
                $validated,
                $requiresPayment,
                $storedProofPath
            ) {
                // Kunci pendaftaran untuk mencegah nomor pengajuan ganda.
                $lockedRegistration = CompetitionRegistration::query()
                    ->whereKey($registration->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Periksa ulang kepemilikan.
                if (
                    (int) $lockedRegistration->user_id
                    !== (int) auth()->id()
                ) {
                    abort(403);
                }

                // Ambil ulang pengajuan terbaru setelah lock.
                $latest = $lockedRegistration->submissions()
                    ->reorder()
                    ->orderByDesc('submission_number')
                    ->lockForUpdate()
                    ->first();

                // Periksa ulang status agar tidak ada pengajuan ganda.
                if (
                    $lockedRegistration->verification_status !== 'rejected'
                    || ! $latest
                    || $latest->status !== 'rejected'
                ) {
                    throw new \RuntimeException(
                        'Pengajuan ini sudah berubah dan tidak dapat diajukan ulang.'
                    );
                }

                $nextSubmissionNumber = (int) $lockedRegistration
                    ->submissions()
                    ->max('submission_number') + 1;

                RegistrationSubmission::create([
                    'registration_id' => $lockedRegistration->id,
                    'submission_number' => $nextSubmissionNumber,
                    'payment_proof_path' => $requiresPayment
                        ? $storedProofPath
                        : null,
                    'payment_description' => $requiresPayment
                        ? ($validated['payment_description'] ?? null)
                        : null,
                    'appeal_message' => $validated['appeal_message'] ?? null,
                    'status' => 'pending',
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                    'rejection_reason' => null,
                ]);

                // Status verifikasi kembali ke antrean pemeriksaan admin.
                $lockedRegistration->update([
                    'verification_status' => 'pending',
                ]);
            });

            return redirect()
                ->route('pendaftaran.status', $registration)
                ->with(
                    'success',
                    'Pengajuan ulang berhasil dikirim. Silakan tunggu pemeriksaan panitia.'
                );

        } catch (Throwable $exception) {
            // Hapus file baru hanya jika proses penyimpanan gagal.
            if ($storedProofPath) {
                Storage::delete($storedProofPath);
            }

            report($exception);

            if (
                $exception instanceof \RuntimeException
                && $exception->getMessage() === 'Pengajuan ini sudah berubah dan tidak dapat diajukan ulang.'
            ) {
                return redirect()
                    ->route('pendaftaran.status', $registration)
                    ->with('error', $exception->getMessage());
            }

            return redirect()
                ->route('pendaftaran.status', $registration)
                ->with(
                    'error',
                    'Terjadi kesalahan saat mengirim pengajuan ulang. Silakan coba kembali.'
                );
        }
    }
}
