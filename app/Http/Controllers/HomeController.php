<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Services\ApiFootballService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    protected $apiFootballService;

    public function __construct(ApiFootballService $apiFootballService)
    {
        $this->apiFootballService = $apiFootballService;
    }

    public function index(): View
    {
        if (Auth::check()) {
            return $this->authenticatedHome();
        }
        
        return $this->guestHome();
    }

    private function authenticatedHome(): View
    {
        // Get user's favorite teams
        $favoriteTeams = Auth::user()->favoriteTeams()->take(6)->get();
        
        // Get recent matches for favorite teams
        $recentMatches = collect();
        foreach ($favoriteTeams as $team) {
            $matches = $this->apiFootballService->getTeamMatches($team->id, now()->year, 3);
            if ($matches && isset($matches['response'])) {
                $recentMatches = $recentMatches->concat(collect($matches['response']));
            }
        }
        
        // Sort by date and take most recent
        $recentMatches = $recentMatches->sortByDesc(function ($match) {
            return $match['fixture']['date'] ?? '';
        })->take(10);

        return view('home.dashboard', compact('favoriteTeams', 'recentMatches'));
    }

    private function guestHome(): View
    {
        // Show featured leagues and general information
        $featuredLeagues = League::where('is_active', true)
            ->whereIn('id', [39, 140, 78, 135, 61]) // EPL, La Liga, Bundesliga, Serie A, Ligue 1
            ->get();

        return view('home.welcome', compact('featuredLeagues'));
    }
}