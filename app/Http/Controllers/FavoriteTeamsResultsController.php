<?php

namespace App\Http\Controllers;

use App\Helpers\LogoHelper;
use App\Services\ApiFootballService;
use Illuminate\Support\Facades\Auth;

class FavoriteTeamsResultsController extends Controller
{
    protected $apiFootballService;

    public function __construct(ApiFootballService $apiFootballService)
    {
        $this->apiFootballService = $apiFootballService;
    }
    public function index()
    {
        // Fetch the user's favorite teams (assuming a relationship exists)
        $favoriteTeams = Auth::user()->favoriteTeams;
        logger(json_encode($favoriteTeams->toArray(), JSON_PRETTY_PRINT));

        // Add API data to each team
        $favoriteTeams->each(function ($team) {
            logger('team', $team->toArray());
            $team['record'] = $this->apiFootballService->getTeamRecord($team['id'], $team['pivot']['league_id'])['response'] ?? [];
            $team['matches'] = collect($this->apiFootballService->getTeamMatches($team['id'], now()->year)['response'] ?? []);
        });

        logger(json_encode($favoriteTeams->toArray(), JSON_PRETTY_PRINT));
        // Pass the enriched data to the view
        return view('favorites.results', compact('favoriteTeams'));
    }
}