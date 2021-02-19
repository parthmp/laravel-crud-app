<?php

use Illuminate\Support\Facades\Route;

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


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->get('/delete', [App\Http\Controllers\CRUDController::class, 'remove']);
Route::middleware('auth')->get('/edit/{id}', [App\Http\Controllers\CRUDController::class, 'edit']);


