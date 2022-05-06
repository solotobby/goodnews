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
Route::get('airtime', [App\Http\Controllers\UserController::class, 'airtime'])->name('airtime');
Route::post('buy/airtime', [App\Http\Controllers\UserController::class, 'buyAirtime'])->name('buy.airtime');
Route::get('data', [App\Http\Controllers\UserController::class, 'data'])->name('data');
Route::get('data/{biller_code}/{biller}', [App\Http\Controllers\UserController::class, 'getDataBundle'])->name('fetch.data');
Route::post('buy/data', [App\Http\Controllers\UserController::class, 'buyData'])->name('buy.data');


Route::get('topup', [App\Http\Controllers\UserController::class, 'topup']);
Route::get('transaction', [App\Http\Controllers\UserController::class, 'transaction']);