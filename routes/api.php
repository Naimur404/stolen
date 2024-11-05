<?php

use App\Http\Controllers\API\ApiAuthController;
use App\Http\Controllers\API\ApiDataController;
use App\Http\Controllers\API\ApiInvoiceController;
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

Route::post('login', [ApiAuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
Route::post('logout', [ApiAuthController::class, 'logout']);
Route::get('customer', [ApiDataController::class, 'getUserApi']);
Route::get('product', [ApiDataController::class, 'getOutletStockApi']);
Route::get('all-invoice', [ApiDataController::class, 'getInvoicesApi']);
Route::get('invoice-details/{id}', [ApiDataController::class, 'sales_details_api']);
Route::get('api-dashbaord', [ApiDataController::class, 'apiDashboard']);
Route::post('customer', [ApiDataController::class, 'createCustomer']);
Route::get('payment-method', [ApiDataController::class, 'getPosData']);
Route::post('invoice-create', [ApiInvoiceController::class, 'store']);

});


