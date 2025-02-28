<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StisticsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('auth.profile');

    Route::get('/family/create', [FamilyController::class, 'create'])->name('family.create');
    Route::post('/family/store', [FamilyController::class, 'store'])->name('family.store');
    Route::get('/family/index', [FamilyController::class, 'index'])->name('family.index');

    Route::resource('transactions', TransactionController::class);

    Route::resource('goals', GoalController::class);

    Route::post('/categories', [CategoryController::class, 'store']);

    Route::get('/statistics', [StisticsController::class, 'index'])->name('statistics.index');

    Route::put('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
});


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
