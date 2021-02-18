<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AnalystController;
use App\Http\Controllers\AppoinmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Auth;

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

/*
Route::get('/', function () {
    return view('welcome');
});
*/

// Create New Appoinment from website
Route::get('/', [AppoinmentController::class,'make']);
Route::get('onlycomponent', [AppoinmentController::class,'onlycomponent']);


//Auth::routes();
Auth::routes(['register' => false]);

Route::prefix('/')
    ->middleware('auth')
    ->group(function () {
        Route::get('/home', [AppoinmentController::class,'make']);
        Route::resource('users', UserController::class);
        Route::resource('appoinments', AppoinmentController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('analysts', AnalystController::class);
        Route::get('attendes', [AppoinmentController::class,'attendes'])->name('attendes');
    });
