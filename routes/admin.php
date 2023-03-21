<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\MiniProjectTagController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TechStackController;
use App\Http\Controllers\Admin\TempFileController;
use App\Http\Controllers\Admin\WorkController;
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

Route::middleware('not.admin')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
});

Route::post('/login', [AuthController::class, 'login'])->name('admin_login');
Route::post('/logout', [AuthController::class, 'logout'])->name('admin_logout');


// upload temporary file using filepond
Route::post('/temp-upload', [TempFileController::class, 'temp_upload']);
Route::delete('/temp-delete', [TempFileController::class, 'temp_delete']);
Route::get('/temp-load', [TempFileController::class, 'temp_load']);

Route::middleware('is.admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard.main');
    })->name('dashboard');

    // Work Controller
    Route::resource('works', WorkController::class);
    Route::get('/work/create-slug', [WorkController::class, 'create_slug']);

    // Service Controller
    Route::resource('services', ServiceController::class);
    Route::get('/service/create-slug', [ServiceController::class, 'create_slug']);

    // Tech Stack Controller
    Route::resource('tech-stacks', TechStackController::class);
    Route::get('/tech-stack/create-slug', [TechStackController::class, 'create_slug']);

    // Mini Project Tag Controller
    Route::resource('mini-project-tags', MiniProjectTagController::class);
    Route::get('/mini-project-tag/create-slug', [MiniProjectTagController::class, 'create_slug']);
});
