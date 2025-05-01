<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class DownloadLogos extends Command
{
    protected $signature = 'logos:download';
    protected $description = 'Download team and competition logos locally';

    public function handle()
    {
        $teams = $this->getTeams();

        // $leagues = $this->getLeagues();

        $this->info('Logos downloaded successfully.');
    }

    protected function saveImage($url, $path)
    {
        if (File::exists($path))
            return;

        $image = Http::get($url)->body();
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $image);
    }

    protected function getLeagues(): void
    {
        $apiUrl = env('FOOTBALL_API_URL') . "/leagues?season=2024";

        $response = $this->getHttpResponse($apiUrl);
        $leagues = $response['response'] ?? [];

        foreach ($leagues as $league) {
            if (isset($league['league']['logo'])) {
                $imageUrl = $league['league']['logo'];
                $id = $league['league']['id'];
                $this->saveImage($imageUrl, public_path("logos/leagues/{$id}.png"));
            }
        }
    }
    protected function getTeams(): void
    {
        $apiUrl = env('FOOTBALL_API_URL') . "/teams?league=78&season=2024";

        $response = $this->getHttpResponse($apiUrl);

        $teams = $response['response'] ?? [];

        foreach ($teams as $team) {
            $url = $team['team']['logo'];
            $id = $team['team']['id'];
            $this->saveImage($url, public_path("logos/teams/{$id}.png"));
        }
    }
    private function getHttpResponse($url): array
    {
        $response = Http::withHeaders([
            'x-rapidapi-host' => env('FOOTBALL_API_HOST'),
            'x-rapidapi-key' => env('FOOTBALL_API_KEY'),
        ])->get($url);
        if ($response->successful()) {
            return $response->json();
        } else {
            $this->error('Failed to fetch data from API.');
            return [];
        }
    }
}
