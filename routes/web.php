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
    return redirect('/login'); //redirect('https://goodnewsinfotech.com/'); //view('welcome');
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
    Route::get('transaction/redirect', [App\Http\Controllers\UserController::class, 'transaction']);
    Route::post('topup', [\App\Http\Controllers\UserController::class, 'topup'])->name('topup');
    Route::get('resolve/{bank_code}/{account_num}', [\App\Http\Controllers\HomeController::class, 'resolveBank']);
    Route::post('resolve/bank', [\App\Http\Controllers\HomeController::class, 'storeBankInformation'])->name('resolve.account');
    Route::post('withdraw', [\App\Http\Controllers\WithdrawalController::class, 'withdraw'])->name('withdraw');


    //////
    Route::get('check', [\App\Http\Controllers\ServiceController::class, 'index']);
});

Route::post('store/smedata', [App\Http\Controllers\AdminController::class, 'storeSME_Data'])->name('store.smedata');
Route::get('create/smedata', [App\Http\Controllers\AdminController::class, 'createSME_Data'])->name('create.smedata');

Route::post('edit/smedata', [App\Http\Controllers\AdminController::class, 'editSME_Data'])->name('edit.smedata');

Route::get('user/list', [App\Http\Controllers\AdminController::class, 'userList'])->name('user.list');
Route::post('fund/wallet', [App\Http\Controllers\AdminController::class, 'fundWallet'])->name('fund.wallet');
Route::get('transaction/{id}', [\App\Http\Controllers\AdminController::class, 'userTransaction'])->name('user.transaction');
Route::get('transaction', [\App\Http\Controllers\AdminController::class, 'transactionList'])->name('transactions');
Route::get('activate/user/{id}', [\App\Http\Controllers\AdminController::class, 'activate']);
Route::get('queue/list', [\App\Http\Controllers\AdminController::class, 'queueList'])->name('queue.list');
Route::get('validate/queue/{id}', [\App\Http\Controllers\AdminController::class, 'validateQueue'])->name('validate.queue');