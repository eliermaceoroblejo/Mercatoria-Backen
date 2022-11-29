<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountGroupController;
use App\Http\Controllers\AccountNatureController;
use App\Http\Controllers\AccountTypesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CurrenciesController;
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

    // Currencies
    Route::post('v1/currencies/all', [CurrenciesController::class, 'getCurrencies']);
    Route::post('v1/currencies/byId', [CurrenciesController::class, 'getCurrencyById']);
    Route::post('v1/currencies/', [CurrenciesController::class, 'addCurrency']);
    Route::put('v1/currencies/', [CurrenciesController::class, 'editCurrency']);
    Route::delete('v1/currencies/', [CurrenciesController::class, 'deleteCurrency']);

    //Accounts
    Route::post('v1/accounts/nature', [AccountNatureController::class, 'getAccountNature']);
    Route::post('v1/accounts/currencies', [CurrenciesController::class, 'getCurrencies']);
    Route::post('v1/accounts/types', [AccountTypesController::class, 'getAccountsTypes']);
    Route::post('v1/accounts', [AccountController::class, 'getAccounts']);
    Route::post('v1/accounts/add', [AccountController::class, 'addAccount']);
    Route::post('v1/accounts/{id}', [AccountController::class, 'findAccount']);
    Route::put('v1/accounts/{id}', [AccountController::class, 'editAccount']);
    Route::delete('v1/accounts/{id}', [AccountController::class, 'deleteAccount']);

    // Accounts Group
    Route::post('v1/groups', [AccountGroupController::class, 'getGroups']);
    // Route::post('v1/groups/{id}', [AccountGroupController::class, 'getGroupById']);
    // Route::post('v1/accounts/groups/add/', [AccountGroupController::class, 'addGroup']);
    // Route::put('v1/groups/{id}', [AccountGroupController::class, 'editGroup']);
    // Route::delete('v1/groups/{id}', [AccountGroupController::class, 'deleteGroup']);

    // Clients
    Route::post('v1/clients/all', [ClientController::class, 'getAll']);
    Route::post('v1/clients/getByCode/{code}', [ClientController::class, 'getByCode']);
    Route::post('v1/clients/getById/{id}', [ClientController::class, 'getById']);
    Route::post('v1/clients/getByDescription/{description}', [ClientController::class, 'getByDescription']);
    Route::post('v1/clients/add', [ClientController::class, 'addClient']);
    Route::put('v1/clients/{id}', [ClientController::class, 'editClient']);
    Route::delete('v1/clients/{id}', [ClientController::class, 'deleteClient']);
});
