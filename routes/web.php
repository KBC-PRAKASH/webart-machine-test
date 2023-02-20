<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ChildrenController
};
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

Route::controller(ChildrenController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/get-option-value', 'getOptionValues');
    Route::post('/handle-registration', 'handleRegistration');
    Route::post('/get-registration-data', 'getRegistrationData');
});

Route::fallback(function() {
    abort(404);
});
