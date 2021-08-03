<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\RwController;
use App\Http\Controllers\RtController;
use App\Http\Controllers\WargaController;




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
    Route::get('/user-profile', [DesaController::class, 'userProfile']);
});
//========================================================================================




//RW API
Route::group(['prefix' => 'rw','middleware' => ['jwt.verify','rw']], function () {
    Route::get('/user-profile', [DesaController::class, 'userProfile']);
});
//========================================================================================




//RT API
Route::group(['prefix' => 'rt','middleware' => ['jwt.verify','rt']], function () {
    Route::get('/user-profile', [DesaController::class, 'userProfile']);
});
//========================================================================================




//WARGA API
Route::group(['prefix' => 'warga','middleware' => ['jwt.verify','warga']], function () {
    Route::get('/user-profile', [DesaController::class, 'userProfile']);
});
//========================================================================================
