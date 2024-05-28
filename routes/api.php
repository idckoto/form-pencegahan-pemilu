<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\MasterdataController;
use App\Http\Controllers\api\PencegahanController;
use App\Http\Controllers\api\ProfileController;
use App\Http\Controllers\api\RekapDataController;
use App\Http\Controllers\api\HitokenController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware(['check.refresh.token'])->group(function () {
    Route::post('/input-form/{id}', [PencegahanController::class, 'store']);
    Route::post('/update-form/{id}', [PencegahanController::class, 'update']);
    Route::post('/hapus-bukti/{id}', [PencegahanController::class, 'destroyBukti']);
    Route::post('/submit-form/{id}', [PencegahanController::class, 'submit']);
    Route::get('/form-laporan/{id}', [PencegahanController::class, 'getFormcegah']);
    Route::get('/form-laporan-detail/{id}', [PencegahanController::class, 'lapFrom']);
    Route::delete('/hapus-laporan/{id}', [PencegahanController::class, 'destroy']);
    Route::get('/view-laporan/{id}/{idform}', [PencegahanController::class, 'viewForm']);

    // ... tambahkan rute lain di sini yang ingin Anda lindungi dengan middleware tersebut
});
Route::post('/login', [AuthController::class, 'index']);


// Route::post('/input-form/{id}', [PencegahanController::class, 'store']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'status'            => true,
        'message'           => 'Lihat Pengguna',
        'data'               => $request->user()
    ]);
});

Route::get('/petugas', [MasterdataController::class, 'petugas']);
Route::get('/tahapan', [MasterdataController::class, 'tahapans']);
Route::get('/jenis', [MasterdataController::class, 'jenis']);
Route::get('/bentuk', [MasterdataController::class, 'bentuks']);
Route::get('/tujuan', [MasterdataController::class, 'tujuans']);
Route::get('/sasaran', [MasterdataController::class, 'sasarans']);
Route::get('/provinsi', [MasterdataController::class, 'provinsi']);
Route::get('/kabupaten', [MasterdataController::class, 'kab']);
Route::get('/kecamatan', [MasterdataController::class, 'kec']);
Route::get('/desa', [MasterdataController::class, 'desa']);
Route::post('/pengguna/update', [MasterdataController::class, 'updatePengguna']);
Route::post('/rekap', [RekapDataController::class, 'rekap'])->middleware('cors');
Route::get('/laporan-pencegahan', [PencegahanController::class, 'index']);
Route::post('/post/store', [PencegahanController::class, 'stores']);
// Route::post('/input-form', [PencegahanController::class, 'store']);
Route::post('/users/delete', [ProfileController::class, 'deleteByEmail']);
Route::post('/users/{id}', [ProfileController::class, 'update']);


Route::get('/user-akses', [ProfileController::class, 'index']);
Route::post('/user-akses', [ProfileController::class, 'store']);

Route::get('/show-user-kab/{id}', [ProfileController::class, 'showUserKab']);
Route::get('/show-user-kec/{id}', [ProfileController::class, 'showUserKec']);


// Route::post('/update-form/{id}', [PencegahanController::class, 'update']);
Route::middleware('auth:sanctum')->group(function () {
});
Route::post('/input-form-tes', [PencegahanController::class, 'simpan']);
Route::controller(HitokenController::class)->group(function () {
    Route::get('/login-mobile', 'index');
});
