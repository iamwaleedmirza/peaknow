<?php

use Illuminate\Http\Request;
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
Route::get('plans', ['App\Http\Controllers\Api\PlanController', 'FetchPlan'])->middleware('allow-token');
Route::group(['middleware' => 'api.token'], function () {
Route::get('/user/{order_id}', ['App\Http\Controllers\Api\UserDetailsController', 'getUserInfo']);
    // Route::post('/order/update', ['App\Http\Controllers\Api\UserDetailsController', 'updateOrderStatus']);
    Route::post('/get/order/shipping-details', ['App\Http\Controllers\Api\UserDetailsController', 'getOrderShippingDetail']);
});

// Webhook for beluga
Route::post('/visit/handle-events', ['App\Http\Controllers\Beluga\BelugaWebhookController', '__invoke'])
    ->middleware('check.api.access');