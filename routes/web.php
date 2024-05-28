<?php

use App\Http\Controllers\BentukController;
use App\Http\Controllers\GraphController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\InputformController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SasaranController;
use App\Http\Controllers\TahapanController;
use App\Http\Controllers\TujuanController;
use App\Http\Controllers\Useakses_pController;
use App\Http\Controllers\UseaksesController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\UtamaController;
use App\Http\Controllers\ChangePasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return redirect('/login');
});

Route::controller(ChangePasswordController::class)->group(function () {
    Route::post('/forgot/submit', 'submitForgot');
    Route::get('/forgot/change/{id}', 'submitChange');
    Route::post('/forgot/confirm', 'confirmForgot');
});

Route::group(['active_menu' => 'welcome'], function () {
    Route::controller(BerandaController::class)->group(function () {
        Route::get('/welcome', 'index');

    });
});

Route::group(['active_menu' => 'dashboard'], function () {
    Route::controller(GraphController::class)->group(function () {
        Route::get('/dashboard', 'indra')->name('dashboard');
        Route::post('/dashboard', 'indra');
        
        Route::post('/dashboard/fetch-wilayah', 'fetchWilayah');
        Route::get('/dashboard/detail/{name}/{date_start}/{date_finish}', 'indexDetail')->name('graphDetail');
        Route::get('/dashboard-jenis', 'indexJenis');
        Route::post('/dashboard-jenis', 'indexJenis');
        
        
        Route::get('/recap', 'indra')->name('recap');
        Route::post('/recap', 'indra');
        //Route::get('/recap/detail/{name}/{date_start}/{date_finish}', 'indexDetail')->name('graphDetail');
        Route::get('/recap-jenis', 'indexJenis');
        Route::post('/recap-jenis', 'indexJenis');
        Route::post('/recap/all-sums', 'getAllsums');
        Route::post('/recap/filter-all-sums', 'filterAllsums');

        Route::post('/recap/data-tahap', 'dataTahap');
        Route::post('/recap/data-bentuk', 'dataBentuk');
        Route::post('/recap/data-bentuk-ri', 'dataBentukRI');
        Route::post('/recap/data-jenis', 'dataJenis');
        Route::post('/recap/data-pencegahan', 'dataPencegahan');
        

    });
});

Route::controller(UseaksesController::class)->group(function () {
    Route::get('/user-akses', 'index');
    Route::put('/profil-update-user/{id}', 'update');

    Route::get('/user-kab-show/{id}', 'show_user_kab');
    Route::get('/user-kec-show/{id}', 'show_user_kec');

    Route::get('/user-akses-create', 'create');
    Route::post('/user-akses', 'store');
    Route::post('/user-provinsi-akses', 'store_user_provinsi');
    Route::post('/user-kab-kota-akses', 'store_user_kabupaten');
    Route::post('/user-kec-akses', 'store_user_kecamatan');

    Route::get('/user-akses-show/{id}', 'show');
    Route::get('/user-akses-show_tes/{id}', 'show_tes');
    Route::delete('/user-akses/{id}/destroy', 'destroy');

    Route::get('/getkab', 'getKabupaten');
    Route::get('/getkec', 'getKecamatan');
    Route::get('/getdesa', 'getDesa');
});
Route::get('/user-akses-show_tes/{id}', [UseaksesController::class, 'show_tes'])->name('user.show_tes');

Route::group(['open_menu' => 'data_master'], function () {
    Route::group(['active_menu' => 'petugas'], function () {
        Route::controller(PetugasController::class)->group(function () {
            Route::get('/petugas', 'index');
            Route::get('/tambah-petugas', 'create');
            Route::get('/edit-petugas/{id}', 'edit');
            Route::put('/edit-petugas/{id}', 'update');
            Route::post('/simpan-petugas', 'store');
            Route::delete('/hapus-petugas', 'destroy');
        });
    });
    Route::group(['active_menu' => 'tahapan'], function () {
        Route::controller(TahapanController::class)->group(function () {
            Route::get('/tahapan', 'index');
            Route::get('/tambah-tahapan', 'create');
            Route::get('/edit-tahapan/{id}', 'edit');
            Route::put('/edit-tahapan/{id}', 'update');
            Route::post('/simpan-tahapan', 'store');
            Route::delete('/hapus-tahapan', 'destroy');
        });
    });
    Route::group(['active_menu' => 'jenis'], function () {
        Route::controller(JenisController::class)->group(function () {
            Route::get('/jenis', 'index');
            Route::get('/tambah-jenis', 'create');
            Route::get('/edit-jenis/{id}', 'edit');
            Route::put('/edit-jenis/{id}', 'update');
            Route::post('/simpan-jenis', 'store');
            Route::delete('/hapus-jenis', 'destroy');
        });
    });
    Route::group(['active_menu' => 'bentuk'], function () {
        Route::controller(BentukController::class)->group(function () {
            Route::get('/bentuk', 'index');
            Route::get('/detail-bentuk', 'detail');
            Route::get('/ajax-bentuk-prov', 'ajaxBentukProv');
            Route::get('/ajax-bentuk-kab', 'ajaxBentukKab');
            Route::get('/ajax-bentuk-kec', 'ajaxBentukKec');
            Route::get('/ajax-bentuk-kel', 'ajaxBentukKel');
            Route::get('/tambah-bentuk', 'create');
            Route::get('/edit-bentuk/{id}', 'edit');
            Route::put('/edit-bentuk/{id}', 'update');
            Route::post('/simpan-bentuk', 'store');
            Route::delete('/hapus-bentuk', 'destroy');
        });
    });
    Route::group(['active_menu' => 'tujuan'], function () {
        Route::controller(TujuanController::class)->group(function () {
            Route::get('/tujuan', 'index');
            Route::get('/tambah-tujuan', 'create');
            Route::get('/edit-tujuan/{id}', 'edit');
            Route::put('/edit-tujuan/{id}', 'update');
            Route::post('/simpan-tujuan', 'store');
            Route::delete('/hapus-tujuan', 'destroy');
        });
    });
    Route::group(['active_menu' => 'sasaran'], function () {
        Route::controller(SasaranController::class)->group(function () {
            Route::get('/sasaran', 'index');
            Route::get('/tambah-sasaran', 'create');
            Route::get('/edit-sasaran/{id}', 'edit');
            Route::put('/edit-sasaran/{id}', 'update');
            Route::post('/simpan-sasaran', 'store');
            Route::delete('/hapus-sasaran', 'destroy');
        });
    });
    Route::group(['active_menu' => 'wilayah'], function () {
        Route::controller(WilayahController::class)->group(function () {
            Route::get('/wilayah', 'index');
            Route::get('/tambah-wilayah', 'create');
            Route::get('/edit-wilayah/{id}', 'edit');
            Route::put('/edit-wilayah/{id}', 'update');
            Route::post('/simpan-wilayah', 'store');
            Route::delete('/hapus-wilayah', 'destroy');
        });
    });
});
Route::controller(Useakses_pController::class)->group(function () {
    Route::get('/profil', 'pengaturan');
    Route::patch('/profil-update', 'update');
    Route::patch('/profil-foto', 'update_image');
});
Route::group(['open_menu' => 'input-laporan'], function () {
    Route::group(['active_menu' => 'input_list'], function () {
        Route::controller(InputformController::class)->group(function () {
            Route::get('/cek', 'cek');
            Route::get('/input-form', 'create');
            Route::post('/simpan-form', 'store');
            Route::post('/update-form', 'update');
            Route::get('/list-form', 'index');
            Route::get('/dowload-bukti/{id}', 'download_file');
            Route::get('/cetak-form/{id}', 'cetakForm');
            Route::get('/edit-pencegah/{id}', 'edit');
            Route::delete('/hapus-laporan', 'destroy');
            Route::delete('/hapus-bukti', 'destroyBukti');
            Route::post('/submit-laporan', 'submit');
            Route::get('/provinces', 'provinsi')->name('provinces.select');
            Route::get('/regencies', 'kabupaten')->name('regencies.select');
            Route::get('/districts', 'kecamatan')->name('districts.select');
            Route::get('/villages', 'kelurahan')->name('villages.select');
            Route::get('/tes', 'gambar')->name('image.store');

        });
    });

    Route::group(['open_menu' => 'input-laporan'], function () {
        Route::group(['active_menu' => 'input_list'], function () {
            Route::controller(InputformController::class)->group(function () {
                Route::get('/input-form', 'create');
                Route::post('/simpan-form', 'store');
                Route::post('/update-form', 'update');
                Route::get('/list-form', 'index');
                Route::get('/dowload-bukti/{id}', 'download_file');
                Route::get('/cetak-form/{id}', 'cetakForm');
                Route::get('/edit-pencegah/{id}', 'edit');
                Route::delete('/hapus-laporan', 'destroy');
                Route::delete('/hapus-bukti', 'destroyBukti');
                Route::post('/submit-laporan', 'submit');
                Route::get('/provinces', 'provinsi')->name('provinces.select');
                Route::get('/regencies', 'kabupaten')->name('regencies.select');
                Route::get('/districts', 'kecamatan')->name('districts.select');
                Route::get('/villages', 'kelurahan')->name('villages.select');
                // Route::post('/image-upload', 'gambar')->name('image.store');

            });
        });
        Route::group(['active_menu' => 'laporan_list'], function () {
            Route::controller(InputformController::class)->group(function () {
                Route::get('/laporan-form', 'lapFrom');
                Route::get('/formcegah-data', 'getFormcegahData')->name('formcegah.data');

            });
        });
    });
});
require __DIR__ . '/auth.php';
