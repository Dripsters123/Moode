<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpotifyController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route for the categories view
Route::get('/categories', function () {
    return view('categories');
})->middleware(['auth', 'verified'])->name('categories');

Route::middleware('auth')->group(function () {
    Route::post('/save-to-history', [SpotifyController::class, 'saveToHistory']);
    Route::get('/history', [SpotifyController::class, 'getHistory'])->name('history');
});
require __DIR__.'/auth.php';
