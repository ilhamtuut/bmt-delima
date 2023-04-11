<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepositoController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\MutationController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\SettingController;

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
    return redirect('/home');
});

Route::get('/verification/otp', function () {
    return view('auth.otp');
});
Route::get('/referral/{code}', [App\Http\Controllers\Auth\RegisterController::class, 'referal'])->name('referal');
Auth::routes(['verify' => true]);
Route::group(['middleware' => ['auth','verified','block-user']], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function() {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
        Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::post('/uploadFoto', [ProfileController::class, 'uploadFoto'])->name('upload.foto');
        Route::get('/settings/bank', [ProfileController::class, 'bank'])->name('bank');
        Route::post('/settings/bank', [ProfileController::class, 'updateBank'])->name('updateBank');
    });

    Route::group(['prefix' => 'deposito', 'as' => 'deposito.'], function() {
        Route::get('/', [DepositoController::class, 'index'])->name('index');
        Route::post('/save', [DepositoController::class, 'save'])->name('save');
        Route::get('/payment/{id}', [DepositoController::class, 'payment'])->name('payment');
        Route::post('confirm/payment/{id}', [DepositoController::class, 'confirm_payment'])->name('confirm_payment');
        Route::group(['middleware'=>'permission:administrator'], function() {
            Route::get('/add', [DepositoController::class, 'add'])->name('add');
            Route::post('/create', [DepositoController::class, 'create'])->name('create');
            Route::get('/list', [DepositoController::class, 'list'])->name('list');
            Route::get('/list/komisi', [DepositoController::class, 'list_affilate'])->name('list_affilate');
            Route::get('/action/{type}/{id}', [DepositoController::class, 'action_deposito'])->name('action_deposito');
        });
    });

    Route::group(['prefix' => 'transfer', 'as' => 'transfer.'], function() {
        Route::get('/', [TransferController::class, 'index'])->name('index');
        Route::get('/valid', [TransferController::class, 'valid_account'])->name('valid_account');
        Route::post('/send', [TransferController::class, 'send'])->name('send');
    });

    Route::group(['prefix' => 'mutation', 'as' => 'mutation.'], function() {
        Route::get('/', [MutationController::class, 'index'])->name('index');
        Route::group(['middleware'=>'permission:administrator'], function() {
            Route::get('/balance', [MutationController::class, 'balance'])->name('balance');
            Route::get('/history/{id}', [MutationController::class, 'history'])->name('history');
        });
    });

    Route::group(['prefix' => 'withdrawal', 'as' => 'withdrawal.'], function() {
        Route::get('/', [WithdrawalController::class, 'index'])->name('index');
        Route::post('/send', [WithdrawalController::class, 'send'])->name('send');
        Route::group(['middleware'=>'permission:administrator'], function() {
            Route::get('/list', [WithdrawalController::class, 'list'])->name('list');
            Route::get('/action/{type}/{id}', [WithdrawalController::class, 'action'])->name('action');
        });
    });

    Route::group(['prefix' => 'users', 'as' => 'users.','middleware'=>'permission:administrator'], function() {
        Route::get('/create', [UsersController::class, 'index'])->name('index');
        Route::get('/list/{role}', [UsersController::class, 'list'])->name('list');
        Route::post('/create', [UsersController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [UsersController::class, 'edit'])->name('edit');
        Route::get('/block/{id}', [UsersController::class, 'block'])->name('block');
        Route::post('/update/{id}', [UsersController::class, 'update'])->name('update');
        Route::get('/affiliasi', [UsersController::class, 'referral'])->name('referral');
        Route::get('/affiliasi/{id}', [UsersController::class, 'list_referral'])->name('list_referral');
        Route::get('/settings/affiliasi/{type}/{id}', [UsersController::class, 'set_referral'])->name('set.referral');
    });

    Route::group(['prefix' => 'setting', 'as' => 'setting.','middleware'=>'permission:administrator'], function() {
        Route::get('/withdrawal', [SettingController::class, 'index'])->name('index');
        Route::post('/withdrawal/update', [SettingController::class, 'update'])->name('update');
        Route::get('/bank', [SettingController::class, 'bank'])->name('bank');
        Route::post('/bank/store', [SettingController::class, 'store_bank'])->name('store_bank');
        Route::get('/bank_account', [SettingController::class, 'bank_account'])->name('bank_account');
        Route::post('/bank_account/store', [SettingController::class, 'store_bank_account'])->name('store_bank_account');
        Route::get('/deposito', [SettingController::class, 'deposito'])->name('deposito');
        Route::post('/deposito/store', [SettingController::class, 'store_deposito'])->name('store_deposito');
    });
});
