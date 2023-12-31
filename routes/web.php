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
Route::view('/list', 'newmap');
Route::view('/editform', 'newsecm');
Route::get('/pins', [PinController::class, 'index']);
Route::post('/create', [PinController::class, 'store']);
Route::get('/pins/{id}', [PinController::class, 'destroy']);

Route::get('/edit', [PinController::class, 'update']);
Route::get('/show/{id}', [PinController::class, 'show']);
Route::get('/csrf', function () {
    return response()->json(['csrfToken' => csrf_token()]);
  });
