<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Helpers\LogoHelper;

class MatchController extends Controller
{
    public function index(): View
    {
        $apiUrl = env('FOOTBALL_API_URL') . "/leagues?season=2025";

        try {
            $response = Http::withHeaders([
                'x-rapidapi-host' => env('FOOTBALL_API_HOST'),
                'x-rapidapi-key' => env('FOOTBALL_API_KEY'),
            ])->get($apiUrl);

            $leagues = $response->successful()
                ? $response->json()['response'] ?? []
                : [];

        } catch (\Exception $e) {
            Log::error('Error fetching leagues: ' . $e->getMessage());
            $leagues = [];
        }

        return view('leagues', ['leagues' => $leagues]);
    }

    public function fetchLeagues(): View
    {
        $apiUrl = env('FOOTBALL_API_URL') . "/leagues?season=2025";

        try {
            $response = Http::withHeaders([
                'x-rapidapi-host' => env('FOOTBALL_API_HOST'),
                'x-rapidapi-key' => env('FOOTBALL_API_KEY'),
            ])->get($apiUrl);

            $leagues = $response->successful()
                ? $response->json()['response'] ?? []
                : [];
            $leagues = array_map(function ($league) {
                return [
                    'id' => $league['league']['id'],
                    'name' => $league['league']['name'],
                    'logo' => $league['league']['logo'],
                    'country' => $league['country'],
                    'season' => $league['seasons'][0]['year'] ?? null,
                ];
            }, $leagues);

            $leagues = LogoHelper::addLeagueLogos(
                $leagues,
            );

        } catch (\Exception $e) {
            Log::error('Error fetching leagues: ' . $e->getMessage());
            $leagues = [];
        }

        return view('leagues', ['leagues' => $leagues]);
    }
    public function fetchTeams(): View
    {
        $apiUrl = env('FOOTBALL_API_URL') . "/teams?league=" . config('constants.LEAGUE_ID_MLS') . "&season=2024";

        try {
            $response = Http::withHeaders([
                'x-rapidapi-host' => env('FOOTBALL_API_HOST'),
                'x-rapidapi-key' => env('FOOTBALL_API_KEY'),
            ])->get($apiUrl);

            $teams = $response->successful()
                ? $response->json()['response'] ?? []
                : [];
            $teams = array_map(function ($team) {
                return [
                    'id' => $team['team']['id'],
                    'name' => $team['team']['name'],
                    'logo' => $team['team']['logo'],
                    'country' => $team['team']['country'],
                    'national' => $team['team']['national'],
                ];
            }, $teams);

            $teams = LogoHelper::addTeamLogos(
                $teams,
            );
        } catch (\Exception $e) {
            Log::error('Error fetching leagues: ' . $e->getMessage());
            $teams = [];
        }

        return view('teams', ['teams' => $teams]);
    }
}
