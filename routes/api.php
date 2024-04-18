<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\WalletController;
use App\Http\Controllers\Api\V1\TransactionController;
use App\Http\Controllers\Api\V1\ExpenseCategoryController;

Route::group(['prefix' => 'v1'], function () {
    Route::apiResource('wallets', WalletController::class);
});

Route::group(['prefix' => 'v1'], function () {
    Route::apiResource('transactions', TransactionController::class);
});

//get wallets by user id
Route::get('/v1/users/{user_id}/wallets', [WalletController::class, 'getWalletsByUserId']);

//get transactions by wallet
Route::get('/v1/wallets/{wallet_id}/transactions', [TransactionController::class, 'getTransactionsByWallet']);

//get categories
Route::get('/v1/categories', [ExpenseCategoryController::class, 'index']);

//get transactions of wallet amount by date period
Route::get('/v1/wallets/{wallet_id}/transactions/expenses', [TransactionController::class, 'getExpense']);

Route::get('/v1/wallets/{wallet_id}/transactions/incomes', [TransactionController::class, 'getIncome']);


Route::get('/v1/wallets/{wallet_id}/transactions/search', [TransactionController::class, 'searchTransactions']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

