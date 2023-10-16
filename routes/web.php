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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => [
    'auth:sanctum',
    // 'Authorization'
]], function () {
    Route::prefix('users')->group(function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::get('create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
        Route::post('store', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
        Route::get('{id}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');
        Route::get('edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
        Route::patch('{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
        Route::DELETE('{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.delete');
    });

    Route::prefix('tickets')->group(function () {
        Route::get('/', [App\Http\Controllers\TicketsController::class, 'index'])->name('tickets.index');
        Route::get('create', [App\Http\Controllers\TicketsController::class, 'create'])->name('tickets.create')->middleware('Authorization');
        Route::post('store', [App\Http\Controllers\TicketsController::class, 'store'])->name('tickets.store');
        Route::get('{id}', [App\Http\Controllers\TicketsController::class, 'show'])->name('tickets.show');
        Route::get('edit/{id}', [App\Http\Controllers\TicketsController::class, 'edit'])->name('tickets.edit');
        Route::patch('{id}', [App\Http\Controllers\TicketsController::class, 'update'])->name('tickets.update')->middleware('Authorization');
        Route::delete('{id}', [App\Http\Controllers\TicketsController::class, 'destroy'])->name('tickets.delete');
    });
});
