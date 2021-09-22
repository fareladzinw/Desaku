<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\RwController;
use App\Http\Controllers\RtController;
use App\Http\Controllers\WargaController;

use App\Http\Controllers\BeritaController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SuratController;






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







//API WITHOUT JWT VERIFY
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);
//========================================================================================






//API WITH JWT WITHOUT PREFIX
Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    
    //BERITA API
    Route::get('/show-berita', [BeritaController::class, 'showBerita']);
    Route::post('/show-berita/filter', [BeritaController::class, 'showBeritaFilter']);

    //Kegiatan API
    Route::get('/show-kegiatan', [KegiatanController::class, 'showKegiatan']);
    Route::post('/show-kegiatan/filter', [KegiatanController::class, 'showKegiatanFilter']);

    //Laporan API
    Route::get('/show-laporan', [LaporanController::class, 'showLaporan']);
    Route::post('/show-laporan/filter', [LaporanController::class, 'showLaporanFilter']);

    //Surat API
    Route::get('/show-surat', [SuratController::class, 'showSurat']);
    Route::post('/show-surat/filter', [SuratController::class, 'showSuratFilter']);
});
//========================================================================================







//DESA, RW, RT ACCESS API
Route::group(['middleware' => ['jwt.verify','desarwrt.access']], function () {

    Route::group(['prefix'=>'laporan'],function(){
        Route::post('/validasi', [LaporamController::class, 'validasiLaporan']);
    });

    Route::group(['prefix'=>'surat'],function(){
        Route::post('/change-status', [SuratController::class, 'changeStatusSurat']);
    });
});
//========================================================================================








//ADMIN API
Route::group(['prefix' => 'admin','middleware' => ['jwt.verify','admin']], function () {
    Route::get('/user-profile', [AdminController::class, 'userProfile']);

    //API CRUD User 
    Route::post('/register-user', [AdminController::class, 'registerUser']);
    Route::post('/update-user', [AdminController::class, 'updateUser']);
    Route::post('/delete-user', [AdminController::class, 'deleteUser']);
    Route::get('/show-user', [AdminController::class, 'showUser']);
    Route::post('/show-user/filter', [AdminController::class, 'showUserFilter']);
    //==================================================================

    //API Desa
    Route::post('/create-desa', [AdminController::class, 'createDesa']);
    Route::post('/update-desa', [AdminController::class, 'updateDesa']);
    Route::post('/delete-desa', [AdminController::class, 'deleteDesa']);
    Route::get('/show-desa', [AdminController::class, 'showDesa']);
    //==================================================================

    //API RW
    Route::post('/create-rw', [AdminController::class, 'createRw']);
    Route::post('/update-rw', [AdminController::class, 'updateRw']);
    Route::post('/delete-rw', [AdminController::class, 'deleteRw']);
    Route::get('/show-rw', [AdminController::class, 'showRw']);
    //==================================================================

    //API RT
    Route::post('/create-rt', [AdminController::class, 'createRt']);
    Route::post('/update-rt', [AdminController::class, 'updateRt']);
    Route::post('/delete-rt', [AdminController::class, 'deleteRt']);
    Route::get('/show-rt', [AdminController::class, 'showRt']);
    //==================================================================
});
//========================================================================================






//DESA API
Route::group(['prefix' => 'desa','middleware' => ['jwt.verify','desa']], function () {

    //CRUD USER ( Warga, RW, RT )
    Route::post('/register-user', [DesaController::class, 'registerUser']);
    Route::post('/update-user', [DesaController::class, 'updateUser']);
    Route::post('/delete-user', [DesaController::class, 'deleteUser']);
    Route::get('/show-user', [DesaController::class, 'showUser']);
    //==============================================================


    //BERITA
    Route::post('/create-berita', [DesaController::class, 'createBerita']);
    Route::post('/delete-berita', [DesaController::class, 'deleteBerita']);
    //===============================================================

    //Kegiatan
    Route::post('/create-kegiatan', [DesaController::class, 'createKegiatan']);
    Route::post('/delete-kegiatan', [DesaController::class, 'deleteKegiatan']);
    //===============================================================


});
//========================================================================================







//RW API
Route::group(['prefix' => 'rw','middleware' => ['jwt.verify','rw']], function () {

    //CRUD USER ( Warga, RT )
    Route::post('/register-user', [RwController::class, 'registerUser']);
    Route::post('/update-user', [RwController::class, 'updateUser']);
    Route::post('/delete-user', [RwController::class, 'deleteUser']);
    Route::get('/show-user', [RwController::class, 'showUser']);
    //==============================================================

    //BERITA
    Route::post('/create-berita', [RwController::class, 'createBerita']);
    Route::post('/delete-berita', [RwController::class, 'deleteBerita']);
    //===============================================================

    //Kegiatan
    Route::post('/create-kegiatan', [RwController::class, 'createKegiatan']);
    Route::post('/delete-kegiatan', [RwController::class, 'deleteKegiatan']);
    //===============================================================
});
//========================================================================================







//RT API
Route::group(['prefix' => 'rt','middleware' => ['jwt.verify','rt']], function () {

    //CRUD USER ( Warga, RT )
    Route::post('/register-user', [RtController::class, 'registerUser']);
    Route::post('/update-user', [RtController::class, 'updateUser']);
    Route::post('/delete-user', [RtController::class, 'deleteUser']);
    Route::get('/show-user', [RtController::class, 'showUser']);
    //==============================================================

    //BERITA
    Route::post('/create-berita', [RtController::class, 'createBerita']);
    Route::post('/delete-berita', [RtController::class, 'deleteBerita']);
    //===============================================================

    //Kegiatan
    Route::post('/create-kegiatan', [RtController::class, 'createKegiatan']);
    Route::post('/delete-kegiatan', [RtController::class, 'deleteKegiatan']);
    //===============================================================


    //Verifikasi Warga
    Route::get('/show-warga/unverif', [RtController::class, 'showWargaUnverif']);
    Route::post('/verif/warga', [RtController::class, 'verifWarga']);
    //===============================================================

});
//========================================================================================






//WARGA API
Route::group(['prefix' => 'warga','middleware' => ['jwt.verify','warga']], function () {
    Route::get('/user-profile', [WargaController::class, 'userProfile']);
    Route::post('/create-surat', [SuratController::class, 'createSurat']);
    Route::post('/create-laporan', [LaporanController::class, 'createLaporan']);
    
});
//========================================================================================
