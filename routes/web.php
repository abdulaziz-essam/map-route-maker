<?php

use Illuminate\Support\Facades\Route;
use App\Models\Pin;
use App\Http\Controllers\PinController;
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

Route::get('/pins', [PinController::class, 'index']);
Route::get('/create', [PinController::class, 'store']);
Route::post('/delete', [PinController::class, 'destroy']);
Route::get('/edit', [PinController::class, 'update']);
