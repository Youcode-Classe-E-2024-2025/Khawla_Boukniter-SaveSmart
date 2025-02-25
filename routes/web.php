<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FamilyController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::middleware(['auth'])->group(function () {
    Route::get('/family/create', [FamilyController::class, 'create'])->name('family.create');
    Route::post('/family/store', [FamilyController::class, 'store'])->name('family.store');
    Route::get('/family/index', [FamilyController::class, 'index'])->name('family.index');
});

Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
