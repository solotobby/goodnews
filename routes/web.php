<?php

// use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group( ['middleware'=>'auth','role:user'],function() {
    Route::get('airtime', [App\Http\Controllers\UserController::class, 'airtime'])->name('airtime');
    Route::post('buy/airtime', [App\Http\Controllers\UserController::class, 'buyAirtime'])->name('buy.airtime');
    Route::get('data', [App\Http\Controllers\UserController::class, 'data'])->name('data');
    Route::get('data/{biller_code}', [App\Http\Controllers\UserController::class, 'getDataBundle'])->name('fetch.data');
    Route::post('buy/data', [App\Http\Controllers\UserController::class, 'buyData'])->name('buy.data');
    Route::get('sme/data', [App\Http\Controllers\UserController::class, 'SMEData'])->name('smedata');
    Route::post('buy/sme/data', [\App\Http\Controllers\UserController::class, 'buySMEData'])->name('buy.smedata');
    Route::get('topup', [App\Http\Controllers\UserController::class, 'topup']);
    Route::get('transaction', [App\Http\Controllers\UserController::class, 'transaction']);
    Route::post('topup', [\App\Http\Controllers\UserController::class, 'topup'])->name('topup');
});

Route::post('store/smedata', [App\Http\Controllers\AdminController::class, 'storeSME_Data'])->name('store.smedata');
Route::get('create/smedata', [App\Http\Controllers\AdminController::class, 'createSME_Data'])->name('create.smedata');