<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiFootballService
{
    protected $apiUrl;
    protected $apiKey;
    protected $apiHost;

    public function __construct()
    {
        $this->apiHost = config('services.api_football.host', env('FOOTBALL_API_HOST'));
        $this->apiUrl = config('services.api_football.url', env('FOOTBALL_API_URL'));
        $this->apiKey = config('services.api_football.key', env('FOOTBALL_API_KEY'));
    }

    public function getTeamRecord($teamId, $leagueId)
    {
        $headers = [
            'x-rapidapi-key' => $this->apiKey,
            'x-rapidapi-host' => $this->apiHost,
        ];
        $queryParams = [
            'team' => $teamId,
            'season' => now()->year,
            'league' => $leagueId,
        ];
        $url = "{$this->apiUrl}/teams/statistics";

        // Log the full HTTP request details
        logger('HTTP Request Details:', [
            'url' => $url,
            'headers' => $headers,
            'queryParams' => $queryParams,
        ]);

        $response = Http::withHeaders($headers)->get($url, $queryParams);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function getTeamMatches($teamId, $season = null, $last = 1)
    {
        $season ?? now()->year;

        $response = Http::withHeaders([
            'x-rapidapi-key' => $this->apiKey,
            'x-rapidapi-host' => $this->apiHost,
        ])->get("{$this->apiUrl}/fixtures", [
                    'team' => $teamId,
                    'season' => $season,
                    'last' => $last,
                ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function getLeagues($season = null)
    {
        $season = $season ?? now()->year;

        $response = Http::withHeaders([
            'x-rapidapi-key' => $this->apiKey,
            'x-rapidapi-host' => $this->apiHost,
        ])->get("{$this->apiUrl}/leagues", [
            'season' => $season,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}