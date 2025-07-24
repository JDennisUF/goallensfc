<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Helpers\LogoHelper;
use App\Services\CacheService;

class MatchController extends Controller
{
    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function index(): View
    {
        // Use cached leagues data
        $leagues = $this->cacheService->getLeagues()->toArray();

        return view('leagues', ['leagues' => $leagues]);
    }

    public function fetchLeagues(): View
    {
        // Use cached leagues data
        $leagues = $this->cacheService->getLeagues()->toArray();

        return view('leagues', ['leagues' => $leagues]);
    }
    public function fetchTeams(Request $request): View
    {
        // Get all leagues for the selector
        $leagues = \App\Models\League::where('is_active', true)
            ->orderBy('name')
            ->get();

        $teams = [];
        $selectedLeague = null;

        if ($request->filled('league_id')) {
            $leagueId = $request->input('league_id');
            $selectedLeague = $leagues->where('id', $leagueId)->first();
            
            // Get teams from database first
            if ($selectedLeague) {
                $teams = $selectedLeague->teams()->get()->toArray();
                $teams = LogoHelper::addTeamLogos($teams);
            }
            
            // If no teams in database, try to fetch from API
            if (empty($teams)) {
                $apiUrl = env('FOOTBALL_API_URL') . "/teams?league={$leagueId}&season=2024";

                try {
                    $response = Http::withHeaders([
                        'x-rapidapi-host' => env('FOOTBALL_API_HOST'),
                        'x-rapidapi-key' => env('FOOTBALL_API_KEY'),
                    ])->get($apiUrl);

                    if ($response->successful()) {
                        $apiTeams = $response->json()['response'] ?? [];
                        $teams = array_map(function ($team) {
                            return [
                                'id' => $team['team']['id'],
                                'name' => $team['team']['name'],
                                'logo' => $team['team']['logo'],
                                'country' => $team['team']['country'],
                                'national' => $team['team']['national'],
                            ];
                        }, $apiTeams);

                        $teams = LogoHelper::addTeamLogos($teams);
                    }
                } catch (\Exception $e) {
                    Log::error('Error fetching teams: ' . $e->getMessage());
                    $teams = [];
                }
            }
        }

        return view('teams', compact('leagues', 'teams', 'selectedLeague'));
    }
}
