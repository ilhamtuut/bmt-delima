<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    UserController,
    TransferController,
    DepositoController,
    WithdrawalController,
    SettingController,
    MutationController
};

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
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/reset/password', [AuthController::class, 'sendResetPassword']);
Route::get('/app/version', function(){
    $app = \App\Models\AppVersion::first();
    return [
        'versi' => $app->version,
        'message' => 'Silahkan upgrade ke versi '.$app->version
    ];
});
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Resend link to verify email 
    Route::post('/email/verify/resend', [AuthController::class, 'resendEmailVerification'])->middleware(['throttle:6,1']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile', [UserController::class, 'profile']);
    Route::get('/overview', [UserController::class, 'overview']);
    Route::post('/update/password', [UserController::class, 'updatePassword']);
    Route::post('/update/bank', [UserController::class, 'updateBank']);

    // setting
    Route::get('/bank', [SettingController::class, 'bank']);
    Route::get('/bank/account', [SettingController::class, 'bank_account']);
    Route::get('/deposito/type', [SettingController::class, 'deposito']);

    Route::post('/deposito/send', [DepositoController::class, 'send']);
    Route::post('/deposito/confirm/payment/{id}', [DepositoController::class, 'confirm_payment']);
    Route::get('/deposito/history', [DepositoController::class, 'history']);
    Route::get('/deposito/profit/{id}', [DepositoController::class, 'my_profit']);
    Route::post('/withdrawal/send', [WithdrawalController::class, 'send']);
    Route::get('/withdrawal/history', [WithdrawalController::class, 'history']);
    Route::post('/transfer/send', [TransferController::class, 'send']);
    Route::post('/rekening/valid', [TransferController::class, 'valid_account']);
    Route::get('/mutation', [MutationController::class, 'list']);
});
