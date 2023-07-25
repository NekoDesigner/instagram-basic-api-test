<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [\App\Http\Controllers\TestController::class, 'index'])->name('index');

Route::get('/settings', [\App\Http\Controllers\TestController::class, 'settings'])->name('settings');
Route::post('/settings/token/update', [\App\Http\Controllers\TestController::class, 'update'])->name('update');

Route::get('/getting-started', [\App\Http\Controllers\TestController::class, 'gettingStarted'])->name('getting-started');

Route::get('/oauth-redirect', [\App\Http\Controllers\TestController::class, 'oauthRedirect'])->name('oauth-redirect');
