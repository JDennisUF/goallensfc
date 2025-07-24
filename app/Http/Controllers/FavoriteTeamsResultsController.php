<?php

namespace App\Http\Controllers;

use App\Helpers\LogoHelper;
use App\Services\ApiFootballService;
use App\Services\CacheService;
use Illuminate\Support\Facades\Auth;

class FavoriteTeamsResultsController extends Controller
{
    protected $apiFootballService;
    protected $cacheService;

    public function __construct(ApiFootballService $apiFootballService, CacheService $cacheService)
    {
        $this->apiFootballService = $apiFootballService;
        $this->cacheService = $cacheService;
    }
    public function index()
    {
        // Fetch the user's favorite teams
        $favoriteTeams = Auth::user()->favoriteTeams;

        // Add cached API data to each team
        $favoriteTeams->each(function ($team) {
            try {
                // Get team record/statistics from cache
                $recordResponse = $this->cacheService->getTeamRecord($team['id'], $team['pivot']['league_id']);
                $team['record'] = $recordResponse['response'] ?? [];

                // Get recent matches from cache (last 10 matches)
                $matches = $this->cacheService->getTeamMatches($team['id'], now()->year, 10);
                $team['matches'] = collect($matches);

            } catch (\Exception $e) {
                // Log error but don't break the page
                \Log::error("Error fetching cached data for team {$team['name']}: " . $e->getMessage());
                $team['record'] = [];
                $team['matches'] = collect();
            }
        });

        // Pass the enriched data to the view
        return view('favorites.results', compact('favoriteTeams'));
    }
}