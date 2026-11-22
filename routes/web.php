<?php

use Illuminate\Support\Facades\Route;

// Controller halaman publik.
use App\Http\Controllers\FestivalController;
use App\Http\Controllers\CompetitionController;

// Controller autentikasi.
use App\Http\Controllers\AuthController;

// Controller pendaftaran kompetisi.
use App\Http\Controllers\CompetitionRegistrationController;
use App\Http\Controllers\MyRegistrationController;

// Controller administrator.
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CompetitionController as AdminCompetitionController;
use App\Http\Controllers\Admin\CompetitionPaymentController;
use App\Http\Controllers\Admin\CompetitionFormFieldController;
use App\Http\Controllers\Admin\NewsController;

/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK
|--------------------------------------------------------------------------
*/

// Halaman utama website.
Route::get('/', [FestivalController::class, 'index'])
    ->name('home');

// Halaman festival.
Route::get('/festival', [FestivalController::class, 'index'])
    ->name('festival.index');

// Halaman daftar berita.
Route::get('/berita', [FestivalController::class, 'berita'])
    ->name('berita.index');

// Detail berita berdasarkan slug.
Route::get('/berita/{slug}', [FestivalController::class, 'detailBerita'])
    ->name('berita.show');

// Daftar seluruh kompetisi.
Route::get('/kompetisi', [CompetitionController::class, 'index'])
    ->name('kompetisi.index');

// Detail kompetisi berdasarkan slug.
Route::get('/kompetisi/{slug}', [CompetitionController::class, 'show'])
    ->name('kompetisi.show');


/*
|--------------------------------------------------------------------------
| AUTENTIKASI
|--------------------------------------------------------------------------
*/

// Menampilkan halaman login.
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

// Memproses login.
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.store');

// Menampilkan halaman registrasi akun.
Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

// Memproses registrasi akun.
Route::post('/register', [AuthController::class, 'register'])
    ->name('register.store');

// Memproses logout.
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| ROUTE PARTICIPANT
|--------------------------------------------------------------------------
|
| Hanya pengguna yang sudah login dan memiliki role participant
| yang dapat mengakses fitur pendaftaran kompetisi.
|
*/

Route::middleware(['auth', 'role:participant'])->group(function () {

    // Menampilkan formulir pendaftaran kompetisi.
    Route::get('/kompetisi/{slug}/daftar', [
        CompetitionRegistrationController::class,
        'create',
    ])->name('kompetisi.register');

    // Memproses pendaftaran kompetisi.
    Route::post('/kompetisi/{slug}/daftar', [
        CompetitionRegistrationController::class,
        'store',
    ])->name('kompetisi.register.store');

    // Menampilkan seluruh pendaftaran milik peserta.
    Route::get('/pendaftaran', [
        MyRegistrationController::class,
        'index',
    ])->name('pendaftaran.index');

    // Melihat status dan riwayat pendaftaran.
    Route::get('/pendaftaran/{registration}', [
        CompetitionRegistrationController::class,
        'showStatus',
    ])->name('pendaftaran.status');

    // Mengajukan ulang pendaftaran yang ditolak.
    Route::post('/pendaftaran/{registration}/ajukan-ulang', [
        CompetitionRegistrationController::class,
        'resubmit',
    ])->name('pendaftaran.resubmit');

});


/*
|--------------------------------------------------------------------------
| ROUTE ADMINISTRATOR
|--------------------------------------------------------------------------
|
| Semua route di dalam group ini hanya dapat diakses oleh admin.
| Prefix URL: /admin
| Prefix nama route: admin.
|
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        // Dashboard administrator.
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | MANAJEMEN KOMPETISI
        |--------------------------------------------------------------------------
        */

        Route::resource('competitions', AdminCompetitionController::class);


        /*
        |--------------------------------------------------------------------------
        | MANAJEMEN FIELD FORMULIR KOMPETISI
        |--------------------------------------------------------------------------
        */

        // Menampilkan seluruh field formulir kompetisi.
        Route::get('/competitions/{competition}/fields', [
            CompetitionFormFieldController::class,
            'index',
        ])->name('competitions.fields.index');

        // Menampilkan halaman tambah field.
        Route::get('/competitions/{competition}/fields/create', [
            CompetitionFormFieldController::class,
            'create',
        ])->name('competitions.fields.create');

        // Menyimpan field baru.
        Route::post('/competitions/{competition}/fields', [
            CompetitionFormFieldController::class,
            'store',
        ])->name('competitions.fields.store');

        // Menampilkan halaman edit field.
        Route::get('/competitions/{competition}/fields/{field}/edit', [
            CompetitionFormFieldController::class,
            'edit',
        ])->name('competitions.fields.edit');

        // Memperbarui field.
        Route::put('/competitions/{competition}/fields/{field}', [
            CompetitionFormFieldController::class,
            'update',
        ])->name('competitions.fields.update');

        // Menghapus field.
        Route::delete('/competitions/{competition}/fields/{field}', [
            CompetitionFormFieldController::class,
            'destroy',
        ])->name('competitions.fields.destroy');


        /*
        |--------------------------------------------------------------------------
        | MANAJEMEN PESERTA KOMPETISI
        |--------------------------------------------------------------------------
        */

        // Daftar peserta.
        Route::get('/participants', [ParticipantController::class, 'index'])
            ->name('participants.index');

        // Membuka bukti pembayaran dari penyimpanan privat.
        Route::get('/participants/submissions/{submission}/proof', [
            ParticipantController::class,
            'proof',
        ])->name('participants.submissions.proof');

        // Menampilkan detail pendaftaran peserta.
        Route::get('/participants/{registration}', [
            ParticipantController::class,
            'show',
        ])->name('participants.show');

        // Menyetujui pendaftaran peserta.
        Route::patch('/participants/{registration}/approve', [
            ParticipantController::class,
            'approve',
        ])->name('participants.approve');

        // Menolak pengajuan peserta.
        Route::patch('/participants/{registration}/reject', [
            ParticipantController::class,
            'reject',
        ])->name('participants.reject');


        /*
        |--------------------------------------------------------------------------
        | MANAJEMEN USERS
        |--------------------------------------------------------------------------
        */

        // Daftar pengguna.
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        // Form edit pengguna.
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        // Memperbarui pengguna.
        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        // Menghapus pengguna.
        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy');


        /*
        |--------------------------------------------------------------------------
        | MANAJEMEN BERITA
        |--------------------------------------------------------------------------
        */

        Route::resource('news', NewsController::class);


        /*
        |--------------------------------------------------------------------------
        | MANAJEMEN PEMBAYARAN KOMPETISI
        |--------------------------------------------------------------------------
        */

        // Daftar pengaturan pembayaran.
        Route::get('/payments', [CompetitionPaymentController::class, 'index'])
            ->name('payments.index');

        // Form pengaturan pembayaran kompetisi.
        Route::get('/competitions/{competition}/payment', [
            CompetitionPaymentController::class,
            'edit',
        ])->name('payments.edit');

        // Memperbarui pengaturan pembayaran.
        Route::put('/competitions/{competition}/payment', [
            CompetitionPaymentController::class,
            'update',
        ])->name('payments.update');

        // Menambahkan metode pembayaran.
        Route::post('/competitions/{competition}/payment/methods', [
            CompetitionPaymentController::class,
            'storeMethod',
        ])->name('payments.methods.store');

        // Memperbarui metode pembayaran.
        Route::put('/competitions/{competition}/payment/methods/{method}', [
            CompetitionPaymentController::class,
            'updateMethod',
        ])->name('payments.methods.update');

        // Menghapus metode pembayaran.
        Route::delete('/competitions/{competition}/payment/methods/{method}', [
            CompetitionPaymentController::class,
            'destroyMethod',
        ])->name('payments.methods.destroy');

    });
