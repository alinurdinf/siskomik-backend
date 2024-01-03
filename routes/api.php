<?php

use App\Http\Controllers\API\IncomingLetterController as APIIncomingLetterController;
use App\Http\Controllers\API\KrsController;
use App\Http\Controllers\API\MasterKrsController;
use App\Http\Controllers\API\MatakuliahController;
use App\Http\Controllers\API\OutgoingLetterController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\IncomingLetterController;
use App\Models\MasterKrsItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('outgoing-letters', [OutgoingLetterController::class, 'all']);
    Route::post('incoming-letters', [APIIncomingLetterController::class, 'all']);
    Route::post('outgoing-letters/store', [OutgoingLetterController::class, 'store']);
    Route::post('incoming-letters/validation', [APIIncomingLetterController::class, 'validation']);
    Route::post('incoming-letters/send-reply', [APIIncomingLetterController::class, 'sendReply']);

    Route::post('matakuliah/store', [MatakuliahController::class, 'store']);
    Route::get('matakuliah', [MatakuliahController::class, 'fetch']);

    Route::post('krs/store', [MasterKrsController::class, 'store']);
    Route::post('krs-item/store', [MasterKrsController::class, 'store_krs_item']);

    Route::post('krs-mahasiswa/store', [KrsController::class, 'store']);
    Route::post('user', [UserController::class, 'updateProfile']);

    Route::get('krs-mahasiswa/validasi/{krs_id}', [KrsController::class, 'validate_krs']);
});


Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
