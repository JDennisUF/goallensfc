<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Team;

class FetchTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:teams {league_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch teams from API-Football and populate the teams table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $leagueId = $this->argument('league_id');
        $apiUrl = env('FOOTBALL_API_URL') . "/teams?league={$leagueId}&season=2024";
        $apiKey = env('FOOTBALL_API_KEY');

        logger('API URL: ' . $apiUrl);

        $this->info("Fetching teams for league ID {$leagueId} and season 2024...");

        try {
            $response = Http::withHeaders([
                'x-rapidapi-host' => env('FOOTBALL_API_HOST'),
                'x-rapidapi-key' => $apiKey,
            ])->get($apiUrl);

            if ($response->successful()) {
                $teams = $response->json()['response'] ?? [];

                foreach ($teams as $teamData) {
                    $team = $teamData['team'];
                    $country = $team['country'] ?? null;
                    logger('country: ' . $country);
                    logger('id:' . $team['id']);
                    Team::updateOrCreate(
                        ['id' => $team['id']], // Using the API ID as our PK
                        [
                            'name' => $team['name'],
                            'code' => $team['code'] ?? null,
                            'country' => $country,
                            'logo_url' => $team['logo'],
                            'national' => $team['national'] ?? false,
                            'founded' => $team['founded'] ?? null,
                        ]
                    );
                }

                $this->info('Teams table populated successfully!');
            } else {
                $this->error('Failed to fetch teams: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}