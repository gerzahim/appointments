<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AppoinmentController;
use App\Models\Appoinment;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('appoinments', [AppoinmentController::class,'index']);
Route::get('appoinments/{appoinment}', [AppoinmentController::class,'show']);
Route::get('get-services', [AppoinmentController::class, 'getServices']);
Route::get('get-time-available/{date}/{analysts_count}', [AppoinmentController::class,'getTimeAvailable']);
Route::get('get-analysts-active/', [AppoinmentController::class,'getAnalystActiveCount']);
Route::get('list-attendes/{date}', [AppoinmentController::class,'fetchAttendes']);
Route::post('set-appoinment', [AppoinmentController::class,'store']);
Route::get('confirm-appoinment', [AppoinmentController::class,'confirmAppointment']);
