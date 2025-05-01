<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

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

            // usort($leagueData, function ($a, $b) {
            //     return strcmp($a['id'], $b['id']);
            // });
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

            // usort($leagueData, function ($a, $b) {
            //     return strcmp($a['id'], $b['id']);
            // });
        } catch (\Exception $e) {
            Log::error('Error fetching leagues: ' . $e->getMessage());
            $leagues = [];
        }

        return view('leagues', ['leagues' => $leagues]);
    }
    public function fetchTeams(): View
    {
        $apiUrl = env('FOOTBALL_API_URL') . "/teams?league=" . config('constants.LEAGUE_ID_MLS') . "&season=2025";

        try {
            $response = Http::withHeaders([
                'x-rapidapi-host' => env('FOOTBALL_API_HOST'),
                'x-rapidapi-key' => env('FOOTBALL_API_KEY'),
            ])->get($apiUrl);

            $teams = $response->successful()
                ? $response->json()['response'] ?? []
                : [];

            Log::info('Fetched teams: ', [
                'status' => $response->status(),
                'team count' => count($teams),
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching leagues: ' . $e->getMessage());
            $teams = [];
        }

        return view('teams', ['teams' => $teams]);
    }
}
