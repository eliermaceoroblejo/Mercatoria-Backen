<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountGroupController;
use App\Http\Controllers\AccountNatureController;
use App\Http\Controllers\AccountTypesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\BussinessController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientOperationsController;
use App\Http\Controllers\CurrenciesController;
use App\Http\Controllers\EntryAccountsProvidersController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\OperationDetailsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreAccountsController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreProductController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Models\EntryAccountsProviders;
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
    Route::put('v1/users/setCurrentBussiness', [UserController::class, 'setCurrentBussiness']);

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
    Route::post('v1/accounts/clientOperations', [AccountController::class, 'getAllAccountClientOperations']);
    Route::post('v1/accounts/byType', [AccountController::class, 'getAccountByType']);

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
    Route::post('v1/clientoperations/all', [ClientOperationsController::class, 'getAllClientOperations']);

    // Balance
    Route::post('v1/balance', [BalanceController::class, 'getBalance']);

    // Stores
    Route::post('v1/stores/all', [StoreController::class, 'getAll']);
    Route::post('v1/stores/byId', [StoreController::class, 'getById']);
    Route::post('v1/stores', [StoreController::class, 'addStore']);
    Route::put('v1/stores', [StoreController::class, 'editStore']);
    Route::delete('v1/stores', [StoreController::class, 'deleteStore']);

    // Units
    Route::post('v1/units/all', [UnitController::class, 'getAll']);
    Route::post('v1/units/byId', [UnitController::class, 'getById']);
    Route::post('v1/units', [UnitController::class, 'addUnit']);
    Route::put('v1/units', [UnitController::class, 'editUnit']);
    Route::delete('v1/units', [UnitController::class, 'deleteUnit']);

    // Products
    Route::post('v1/products/all', [ProductController::class, 'getAll']);
    Route::post('v1/products/byId', [ProductController::class, 'getById']);
    Route::post('v1/products', [ProductController::class, 'addProduct']);
    Route::put('v1/products', [ProductController::class, 'editProduct']);
    Route::delete('v1/products', [ProductController::class, 'deleteProduct']);

    // Store Products
    Route::post('v1/store-products/all', [StoreProductController::class, 'getAll']);

    // Store Accounts
    Route::post('v1/store-accounts/byStore', [StoreAccountsController::class, 'getByStore']);
    Route::post('v1/store-accounts', [StoreAccountsController::class, 'addStoreAccount']);
    Route::delete('v1/store-accounts', [StoreAccountsController::class, 'deleteStoreAccount']);

    // Entry Accounts Provider
    Route::post('v1/entry-accounts-provider/all', [EntryAccountsProvidersController::class, 'getAll']);
    Route::post('v1/entry-accounts-provider/byId', [EntryAccountsProvidersController::class, 'getById']);
    Route::put('v1/entry-accounts-provider', [EntryAccountsProvidersController::class, 'editEntryAccountsProviders']);

    // Movements
    Route::post('v1/movements/byType', [MovementController::class, 'getByType']);
    Route::post('v1/movements/byId', [MovementController::class, 'getById']);
});
