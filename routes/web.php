<?php

use App\Http\Controllers\FavoriteTeamController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/favorites', [FavoriteTeamController::class, 'index'])->middleware(['auth']);
Route::post('/favorites', [FavoriteTeamController::class, 'store'])->middleware(['auth']);
Route::delete('/favorites/{id}', [FavoriteTeamController::class, 'destroy'])->middleware(['auth']);


Route::get('/', [MatchController::class, 'index']);
Route::get('/leagues', [MatchController::class, 'fetchLeagues']);
Route::get('/teams', [MatchController::class, 'fetchTeams']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
