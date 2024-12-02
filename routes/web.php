<?php

use App\Http\Controllers\AdminPanel\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserPanel\UserController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Route Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/user-list', [UserController::class, 'getAllUser'])->name('admin.user');
    Route::get('user/showFormCreate', [UserController::class, 'showFormCreateUser'])->name('admin.user.create');
    Route::post('user/create', [UserController::class, 'createUser'])->name('admin.user.submit');
    Route::get('/user/{id}/edit', [UserController::class, 'showFormEditUser'])->name('admin.user.edit');
    Route::post('/user/{id}/update', [UserController::class, 'updateUser'])->name('admin.user.update');
    Route::post('/user/{id}/delete', [UserController::class, 'deleteUser'])->name('admin.user.delete');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
});
