<?php

use App\Http\Controllers\AppConfigController;
use App\Http\Controllers\IncomingLetterController;
use App\Http\Controllers\OutgoingLetterController;
use App\Http\Controllers\UserController;
use App\Models\OutgoingLetter;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('app-config', [AppConfigController::class, 'index'])->name('app-config');
    Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('logs');
    Route::get('users', [UserController::class, 'index'])->name('user.index');
    Route::get('outgoing', [OutgoingLetterController::class, 'index'])->name('outgoing.index');
    Route::get('outgoing/create', [OutgoingLetterController::class, 'create'])->name('outgoing.create');
    Route::post('outgoing/store', [OutgoingLetterController::class, 'store'])->name('outgoing.store');
    Route::get('incoming', [IncomingLetterController::class, 'index'])->name('incoming.index');
    Route::get('incoming/show/{id}', [IncomingLetterController::class, 'show'])->name('incoming.show');
    Route::get('incoming/show-pdf/{reference_number}', [IncomingLetterController::class, 'showpdf'])->name('incoming.show-pdf');
    Route::post('incoming/validate', [IncomingLetterController::class, 'validation'])->name('incoming.validate');
});
