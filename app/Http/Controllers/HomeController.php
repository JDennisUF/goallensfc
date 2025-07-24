<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Services\ApiFootballService;
use App\Services\CacheService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    protected $apiFootballService;
    protected $cacheService;

    public function __construct(ApiFootballService $apiFootballService, CacheService $cacheService)
    {
        $this->apiFootballService = $apiFootballService;
        $this->cacheService = $cacheService;
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
        
        // Get unique leagues for the user's favorite teams
        $favoriteLeagues = collect();
        foreach ($favoriteTeams as $team) {
            if ($team->pivot && $team->pivot->league_id) {
                $league = \App\Models\League::find($team->pivot->league_id);
                if ($league && !$favoriteLeagues->contains('id', $league->id)) {
                    $favoriteLeagues->push($league);
                }
            }
        }
        
        // Get recent matches for favorite teams using cache service
        $recentMatches = collect();
        try {
            $favoriteTeamIds = $favoriteTeams->pluck('id')->toArray();
            $matches = $this->cacheService->getMultipleTeamMatches($favoriteTeamIds, 8);
            $recentMatches = collect($matches);
        } catch (\Exception $e) {
            // Log error but don't break the dashboard
            \Log::error('Error fetching recent matches: ' . $e->getMessage());
            $recentMatches = collect();
        }

        return view('home.dashboard', compact('favoriteTeams', 'recentMatches', 'favoriteLeagues'));
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