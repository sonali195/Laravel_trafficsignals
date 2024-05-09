<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignalLightsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/signal-lights', [SignalLightsController::class, 'index']);
Route::post('/signal-lights/start', [SignalLightsController::class, 'start'])->name('storeSignalData');