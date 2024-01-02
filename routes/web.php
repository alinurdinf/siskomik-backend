<?php

use App\Models\OutgoingLetter;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AppConfigController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\IncomingLetterController;
use App\Http\Controllers\OutgoingLetterController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::get('app-config', [AppConfigController::class, 'index'])->name('app-config');

    Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('logs');

    Route::prefix('users')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/register', [UserController::class, 'store'])->name('store');
    });

    Route::prefix('outgoing')->name('outgoing.')->group(function () {
        Route::get('/', [OutgoingLetterController::class, 'index'])->name('index');
        Route::get('create', [OutgoingLetterController::class, 'create'])->name('create');
        Route::post('store', [OutgoingLetterController::class, 'store'])->name('store');
        Route::get('show/{ref_number}', [OutgoingLetterController::class, 'show'])->name('show');
    });

    Route::patch('/fcm-token', [OutgoingLetterController::class, 'updateToken'])->name('fcmToken');
    Route::post('/send-notification', [OutgoingLetterController::class, 'notification'])->name('notification');

    Route::prefix('incoming')->name('incoming.')->group(function () {
        Route::get('/', [IncomingLetterController::class, 'index'])->name('index');
        Route::get('show/{ref_number}', [IncomingLetterController::class, 'show'])->name('show');
        Route::get('show-pdf/{reference_number}', [OutgoingLetterController::class, 'showpdf'])->name('show-pdf');
        Route::get('show-reply/{reference_number}', [IncomingLetterController::class, 'showpdf'])->name('show-reply');
        Route::post('validate', [IncomingLetterController::class, 'validation'])->name('validate');
        Route::post('reply', [IncomingLetterController::class, 'approvalReply'])->name('reply');
    });

    Route::resource('matakuliah', MatakuliahController::class)->only(['index', 'show', 'create', 'store']);
});
