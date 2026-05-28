<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/task/create', [TaskController::class, 'create'])->name('task.create');
    Route::post('/task', [TaskController::class, 'store'])->name('task.store');
    Route::post('/task/{task}/cancel', [TaskController::class, 'cancel'])->name('task.cancel');
    Route::post('/task/{task}/apply', [TaskController::class, 'apply'])->name('task.apply');
    Route::get('/task/{task}', [TaskController::class, 'show'])->name('task.show');
    Route::post('/task/{task}/accept/{application}', [TaskController::class, 'accept'])->name('task.accept');
    
    Route::post('/task/{task}/complete', [TaskController::class, 'complete'])->name('task.complete');
    Route::post('/task/{task}/verify', [TaskController::class, 'verify'])->name('task.verify');
    Route::post('/task/{task}/cancel-progress', [TaskController::class, 'cancelProgress'])->name('task.cancel_progress');
    
    // Activity Route
    Route::get('/activity', [\App\Http\Controllers\ActivityController::class, 'index'])->name('activity.index');
    
    // Chat Routes
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{task}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{task}', [\App\Http\Controllers\ChatController::class, 'store'])->name('chat.store');

    // Review Routes
    Route::get('/task/{task}/review', [\App\Http\Controllers\ReviewController::class, 'create'])->name('review.create');
    Route::post('/task/{task}/review', [\App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');

    // Wallet Routes
    Route::get('/wallet', [\App\Http\Controllers\WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/topup', [\App\Http\Controllers\WalletController::class, 'topup'])->name('wallet.topup');
    Route::post('/wallet/withdraw', [\App\Http\Controllers\WalletController::class, 'withdraw'])->name('wallet.withdraw');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
});
