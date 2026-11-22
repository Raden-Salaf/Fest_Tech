<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionPaymentMethod;
use App\Models\CompetitionPaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use RuntimeException;
use Throwable;

class CompetitionPaymentController extends Controller
{
    /**
     * Daftar pengaturan pembayaran kompetisi.
     */
    public function index()
    {
        $competitions = Competition::with('paymentSetting')
            ->latest()
            ->paginate(10);

        return view('admin.payments.index', compact('competitions'));
    }

    /**
     * Halaman pengaturan pembayaran satu kompetisi.
     */
    public function edit(Competition $competition)
    {
        $competition->load('paymentSetting.paymentMethods');

        return view('admin.payments.edit', compact('competition'));
    }

    /**
     * Simpan pengaturan pembayaran kompetisi.
     */
    public function update(Request $request, Competition $competition)
    {
        $validated = $request->validate([
            'is_payment_required' => [
                'required',
                'boolean',
            ],

            'fee_amount' => [
                'nullable',
                'required_if:is_payment_required,1',
                'integer',
                'min:1',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'instructions' => [
                'nullable',
                'string',
            ],
        ]);

        $isPaymentRequired = $request->boolean('is_payment_required');

        CompetitionPaymentSetting::updateOrCreate(
            [
                'competition_id' => $competition->id,
            ],
            [
                'is_payment_required' => $isPaymentRequired,
                'fee_amount' => $isPaymentRequired
                    ? ($validated['fee_amount'] ?? 0)
                    : 0,
                'description' => $validated['description'] ?? null,
                'instructions' => $validated['instructions'] ?? null,
            ]
        );

        alert()->success(
            'Berhasil',
            'Pengaturan pembayaran berhasil diperbarui.'
        );

        return back();
    }

    /**
     * Tambahkan metode pembayaran baru.
     */
    public function storeMethod(Request $request, Competition $competition)
    {
        $validated = $request->validate([
            'method_type' => [
                'required',
                Rule::in(['bank', 'ewallet', 'qris']),
            ],

            'provider_name' => [
                'required',
                'string',
                'max:255',
            ],

            'account_name' => [
                'nullable',
                'required_if:method_type,bank,ewallet',
                'string',
                'max:255',
            ],

            'account_number' => [
                'nullable',
                'required_if:method_type,bank,ewallet',
                'string',
                'max:255',
            ],

            'image' => [
                'nullable',
                'required_if:method_type,qris',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $paymentSetting = CompetitionPaymentSetting::firstOrCreate(
            [
                'competition_id' => $competition->id,
            ],
            [
                'is_payment_required' => false,
                'fee_amount' => 0,
            ]
        );

        $imagePath = null;

        try {
            if (
                $validated['method_type'] === 'qris'
                && $request->hasFile('image')
            ) {
                $imagePath = $request->file('image')
                    ->store('payment-qris', 'public');

                if (!$imagePath) {
                    throw new RuntimeException(
                        'Gagal menyimpan gambar QRIS.'
                    );
                }
            }

            DB::transaction(function () use (
                $paymentSetting,
                $validated,
                $request,
                $imagePath
            ) {
                $paymentSetting->paymentMethods()->create([
                    'method_type' => $validated['method_type'],

                    'provider_name' => $validated['provider_name'],

                    'account_name' => $validated['method_type'] === 'qris'
                        ? null
                        : ($validated['account_name'] ?? null),

                    'account_number' => $validated['method_type'] === 'qris'
                        ? null
                        : ($validated['account_number'] ?? null),

                    'image_path' => $validated['method_type'] === 'qris'
                        ? $imagePath
                        : null,

                    'sort_order' => $validated['sort_order'] ?? 0,

                    'is_active' => $request->boolean('is_active'),
                ]);
            });
        } catch (Throwable $e) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $e;
        }

        alert()->success(
            'Berhasil',
            'Metode pembayaran berhasil ditambahkan.'
        );

        return back();
    }

    /**
     * Perbarui metode pembayaran.
     */
    public function updateMethod(
        Request $request,
        Competition $competition,
        CompetitionPaymentMethod $method
    ) {
        $method->load('paymentSetting');

        abort_if(
            !$method->paymentSetting
            || (int) $method->paymentSetting->competition_id !== (int) $competition->id,
            404
        );

        $newMethodType = $request->input('method_type');

        /*
         * QRIS membutuhkan gambar baru apabila:
         * - metode sebelumnya bukan QRIS;
         * - QRIS sebelumnya belum memiliki gambar;
         * - file gambar QRIS lama ternyata sudah tidak tersedia.
         */
        $hasExistingQrisImage = $method->method_type === 'qris'
            && !empty($method->image_path)
            && Storage::disk('public')->exists($method->image_path);

        $imageRequired = $newMethodType === 'qris'
            && !$hasExistingQrisImage;

        $validated = $request->validate([
            'method_type' => [
                'required',
                Rule::in(['bank', 'ewallet', 'qris']),
            ],

            'provider_name' => [
                'required',
                'string',
                'max:255',
            ],

            'account_name' => [
                'nullable',
                'required_if:method_type,bank,ewallet',
                'string',
                'max:255',
            ],

            'account_number' => [
                'nullable',
                'required_if:method_type,bank,ewallet',
                'string',
                'max:255',
            ],

            'image' => [
                $imageRequired ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $oldImagePath = $method->image_path;
        $newImagePath = null;

        try {
            /*
             * Simpan gambar baru terlebih dahulu.
             * Gambar lama belum dihapus.
             */
            if ($request->hasFile('image')) {
                $newImagePath = $request->file('image')
                    ->store('payment-qris', 'public');

                if (!$newImagePath) {
                    throw new RuntimeException(
                        'Gagal menyimpan gambar QRIS baru.'
                    );
                }
            }

            DB::transaction(function () use (
                $method,
                $validated,
                $request,
                $newImagePath,
                $hasExistingQrisImage
            ) {
                $method->update([
                    'method_type' => $validated['method_type'],

                    'provider_name' => $validated['provider_name'],

                    'account_name' => $validated['method_type'] === 'qris'
                        ? null
                        : ($validated['account_name'] ?? null),

                    'account_number' => $validated['method_type'] === 'qris'
                        ? null
                        : ($validated['account_number'] ?? null),

                    'image_path' => $validated['method_type'] === 'qris'
                        ? (
                            $newImagePath
                            ?? (
                                $hasExistingQrisImage
                                    ? $method->image_path
                                    : null
                            )
                        )
                        : null,

                    'sort_order' => $validated['sort_order'] ?? 0,

                    'is_active' => $request->boolean('is_active'),
                ]);
            });
        } catch (Throwable $e) {
            /*
             * Database gagal diperbarui.
             * Hapus gambar baru jika sudah terlanjur di-upload.
             */
            if ($newImagePath) {
                Storage::disk('public')->delete($newImagePath);
            }

            throw $e;
        }

        /*
         * Database sudah berhasil diperbarui.
         * Sekarang aman menghapus gambar lama jika:
         * - gambar QRIS diganti; atau
         * - metode diubah menjadi bank/e-wallet.
         *
         * Proses ini sengaja di luar blok catch di atas.
         */
        $shouldDeleteOldImage = $oldImagePath
            && (
                $newImagePath
                || $validated['method_type'] !== 'qris'
            );

        if ($shouldDeleteOldImage) {
            Storage::disk('public')->delete($oldImagePath);
        }

        alert()->success(
            'Berhasil',
            'Metode pembayaran berhasil diperbarui.'
        );

        return back();
    }

    /**
     * Hapus metode pembayaran.
     */
    public function destroyMethod(
        Competition $competition,
        CompetitionPaymentMethod $method
    ) {
        $method->load('paymentSetting');

        abort_if(
            !$method->paymentSetting
            || (int) $method->paymentSetting->competition_id !== (int) $competition->id,
            404
        );

        $imagePath = $method->image_path;

        /*
         * Hapus record database terlebih dahulu.
         */
        DB::transaction(function () use ($method) {
            $method->delete();
        });

        /*
         * Setelah database berhasil, hapus gambar QRIS.
         */
        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        alert()->success(
            'Berhasil',
            'Metode pembayaran berhasil dihapus.'
        );

        return back();
    }
}
