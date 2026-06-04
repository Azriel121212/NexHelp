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
    
    Route::get('/task/{task}/edit', [TaskController::class, 'edit'])->name('task.edit');
    Route::put('/task/{task}', [TaskController::class, 'update'])->name('task.update');
    Route::post('/task/{task}/complete', [TaskController::class, 'complete'])->name('task.complete');
    Route::post('/task/{task}/verify', [TaskController::class, 'verify'])->name('task.verify');
    Route::post('/task/{task}/cancel', [TaskController::class, 'cancelTask'])->name('task.cancel');
    
    // Leaderboard Route
    Route::get('/leaderboard', [\App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboard.index');
    
    // Activity Route
    Route::get('/activity', [\App\Http\Controllers\ActivityController::class, 'index'])->name('activity.index');
    
    // Chat Routes
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{task}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::get('/chat/{task}/messages', [\App\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.messages');
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
    
    // Admin Routes
    Route::get('/admin/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/tasks/pending-html', [\App\Http\Controllers\AdminController::class, 'getPendingTasksHtml'])->name('admin.tasks.pending_html');
    Route::post('/admin/task/{id}/delete', [\App\Http\Controllers\AdminController::class, 'destroyTask'])->name('admin.task.destroy');
    Route::post('/admin/task/{id}/approve', [\App\Http\Controllers\AdminController::class, 'approveTask'])->name('admin.task.approve');
});
