<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountGroupController;
use App\Http\Controllers\AccountNatureController;
use App\Http\Controllers\AccountTypesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\BussinessController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CurrenciesController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\OperationDetailsController;
use App\Http\Controllers\UserController;
use App\Models\ClientOperations;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Auth
Route::post('v1/auth/register', [AuthController::class, 'register']);
Route::post('v1/auth/login', [AuthController::class, 'login']);
Route::post('v1/auth/forgot-password', [AuthController::class, 'forgot_password']);
Route::post('v1/auth/reset-password', [AuthController::class, 'reset_password']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('v1/auth/logout', [AuthController::class, 'logout']);

    // Users
    Route::post('v1/users/setCurrentBussiness', [UserController::class, 'setCurrentBussiness']);

    // Currencies
    Route::post('v1/currencies/all', [CurrenciesController::class, 'getCurrencies']);
    Route::post('v1/currencies/byId', [CurrenciesController::class, 'getCurrencyById']);
    Route::post('v1/currencies', [CurrenciesController::class, 'addCurrency']);
    Route::put('v1/currencies', [CurrenciesController::class, 'editCurrency']);
    Route::delete('v1/currencies', [CurrenciesController::class, 'deleteCurrency']);

    //Accounts
    Route::post('v1/accounts/nature', [AccountNatureController::class, 'getAccountNature']);
    Route::post('v1/accounts/currencies', [CurrenciesController::class, 'getCurrencies']);
    Route::post('v1/accounts/types', [AccountTypesController::class, 'getAccountsTypes']);
    Route::post('v1/accounts/all', [AccountController::class, 'getAccounts']);
    Route::post('v1/accounts', [AccountController::class, 'addAccount']);
    Route::post('v1/accounts/byId', [AccountController::class, 'findAccountById']);
    Route::post('v1/accounts/byNumber', [AccountController::class, 'findAccountByNumber']);
    Route::put('v1/accounts', [AccountController::class, 'editAccount']);
    Route::delete('v1/accounts', [AccountController::class, 'deleteAccount']);
    Route::delete('v1/accounts/lock', [AccountController::class, 'lockAccount']);

    // Accounts Group
    Route::post('v1/groups', [AccountGroupController::class, 'getGroups']);

    // Clients
    Route::post('v1/clients/all', [ClientController::class, 'getAll']);
    Route::post('v1/clients/getByCode', [ClientController::class, 'getByCode']);
    Route::post('v1/clients/getById', [ClientController::class, 'getById']);
    Route::post('v1/clients/getByDescription', [ClientController::class, 'getByDescription']);
    Route::post('v1/clients', [ClientController::class, 'addClient']);
    Route::put('v1/clients', [ClientController::class, 'editClient']);
    Route::delete('v1/clients', [ClientController::class, 'deleteClient']);

    // Bussiness
    Route::post('v1/bussiness/all', [BussinessController::class, 'getAll']);
    Route::post('v1/bussiness/getById', [BussinessController::class, 'getById']);
    Route::post('v1/bussiness', [BussinessController::class, 'addBussiness']);
    Route::delete('v1/bussiness', [BussinessController::class, 'deleteBussiness']);
    Route::put('v1/bussiness', [BussinessController::class, 'editBussiness']);

    // Operations 
    Route::post('v1/operations/all', [OperationController::class, 'getAll']);
    Route::post('v1/operations/byId', [OperationController::class, 'getById']);
    Route::post('v1/operations', [OperationController::class, 'addOperation']);
    Route::post('v1/operations/revert', [OperationController::class, 'revertOperation']);
    Route::post('v1/operations/detail', [OperationDetailsController::class, 'getOperationDetail1']);

    // Client Operations
    Route::post('v1/clientoperations/all', [ClientOperations::class, 'getAll']);

    // Balance
    Route::post('v1/balance', [BalanceController::class, 'getBalance']);
});
