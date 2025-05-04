<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\League;

class FetchLeagues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:leagues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch leagues from API Football and populate the leagues table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiUrl = env('FOOTBALL_API_URL') . '/leagues?id=39&season=2024';
        $apiKey = env('FOOTBALL_API_KEY');

        $this->info('Fetching leagues from API-Football...');

        try {
            $response = Http::withHeaders([
                'x-rapidapi-host' => env('FOOTBALL_API_HOST'),
                'x-rapidapi-key' => $apiKey,
            ])->get($apiUrl);

            if ($response->successful()) {
                $leagues = $response->json()['response'] ?? [];

                foreach ($leagues as $leagueData) {
                    $league = $leagueData['league'];
                    $country = $leagueData['country'];
                    logger('logo: ' . $league['logo']);
                    logger('type: ' . $league['type']);
                    League::updateOrCreate(
                        ['id' => $league['id']], // Using the API ID as our PK
                        [
                            'name' => $league['name'],
                            'logo_url' => $league['logo'],
                            'country' => $country['name'],
                            'code' => $country['code'],
                            'type' => $league['type'],
                        ]
                    );
                }

                $this->info('Leagues table populated successfully!');
            } else {
                $this->error('Failed to fetch leagues: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}