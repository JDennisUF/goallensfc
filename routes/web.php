<?php

use App\Http\Controllers\FavoriteTeamController;
use App\Http\Controllers\FavoriteTeamsResultsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeagueGamesController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/favorites', [FavoriteTeamController::class, 'index'])->middleware(['auth']);
Route::post('/favorites', [FavoriteTeamController::class, 'store'])->middleware(['auth']);
Route::delete('/favorites/{teamid}-{leagueid}', [FavoriteTeamController::class, 'destroy'])->middleware(['auth']);

Route::get('/results', [FavoriteTeamsResultsController::class, 'index'])->middleware(['auth']);
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/leagues', [MatchController::class, 'fetchLeagues'])->name('leagues');
Route::get('/teams', [MatchController::class, 'fetchTeams'])->name('teams');

Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/league-games', [LeagueGamesController::class, 'index'])->middleware(['auth', 'verified'])->name('league-games');

// created by artisan
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
