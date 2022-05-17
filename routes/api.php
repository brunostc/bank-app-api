<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DepositController as AdminDepositController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

use App\Http\Controllers\Customer\DepositController as CustomerDepositController;
use App\Http\Controllers\Customer\PurchaseController as CustomerPurchaseController;
use App\Http\Controllers\Customer\TransactionController as CustomerTransactionController;
use App\Http\Controllers\Customer\BalanceController as CustomerBalanceController;
use App\Http\Controllers\Customer\UserController;

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

Route::group(['prefix' => 'customer', 'namespace' => 'Customer'], function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);

    Route::group(['middleware' => ['auth:sanctum', 'customer']], function () {
        Route::group(['prefix' => 'balance'], function () {
            Route::get('/', [CustomerBalanceController::class, 'getBalance']);
        });

        Route::group(['prefix' => 'deposit'], function () {
            Route::get('/list', [CustomerDepositController::class, 'list']);
            Route::post('/', [CustomerDepositController::class, 'store']);
        });

        Route::group(['prefix' => 'purchase'], function () {
            Route::get('/list', [CustomerPurchaseController::class, 'list']);
            Route::post('/', [CustomerPurchaseController::class, 'store']);
        });

        Route::group(['prefix' => 'transaction'], function () {
            Route::get('/list', [CustomerTransactionController::class, 'list']);
        });
    });
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::group(['middleware' => ['auth:sanctum', 'admin']], function () {
        Route::get('/balance', [AdminDashboardController::class, 'get']);

        Route::group(['prefix' => 'deposit'], function () {
            Route::get('/list/pending', [AdminDepositController::class, 'listPending']);
            Route::post('/{id}/approve', [AdminDepositController::class, 'approve']);
            Route::post('/{id}/reprove', [AdminDepositController::class, 'reprove']);
        });
    });
});
