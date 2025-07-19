<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\League;

class LeagueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leagues = [
            // Top European Leagues
            [
                'api_id' => 39,
                'name' => 'Premier League',
                'country' => 'England',
                'code' => 'GB',
                'type' => 'League',
                'is_active' => true,
            ],
            [
                'api_id' => 140,
                'name' => 'La Liga',
                'country' => 'Spain',
                'code' => 'ES',
                'type' => 'League',
                'is_active' => true,
            ],
            [
                'api_id' => 78,
                'name' => 'Bundesliga',
                'country' => 'Germany',
                'code' => 'DE',
                'type' => 'League',
                'is_active' => true,
            ],
            [
                'api_id' => 135,
                'name' => 'Serie A',
                'country' => 'Italy',
                'code' => 'IT',
                'type' => 'League',
                'is_active' => true,
            ],
            [
                'api_id' => 61,
                'name' => 'Ligue 1',
                'country' => 'France',
                'code' => 'FR',
                'type' => 'League',
                'is_active' => true,
            ],

            // North American Leagues
            [
                'api_id' => 253,
                'name' => 'Major League Soccer',
                'country' => 'USA',
                'code' => 'US',
                'type' => 'League',
                'is_active' => true,
            ],
            [
                'api_id' => 262,
                'name' => 'Liga MX',
                'country' => 'Mexico',
                'code' => 'MX',
                'type' => 'League',
                'is_active' => true,
            ],

            // Other Popular Leagues
            [
                'api_id' => 88,
                'name' => 'Eredivisie',
                'country' => 'Netherlands',
                'code' => 'NL',
                'type' => 'League',
                'is_active' => true,
            ],
            [
                'api_id' => 94,
                'name' => 'Primeira Liga',
                'country' => 'Portugal',
                'code' => 'PT',
                'type' => 'League',
                'is_active' => true,
            ],
            [
                'api_id' => 71,
                'name' => 'Brasileirão Serie A',
                'country' => 'Brazil',
                'code' => 'BR',
                'type' => 'League',
                'is_active' => true,
            ],
            [
                'api_id' => 128,
                'name' => 'Liga Profesional Argentina',
                'country' => 'Argentina',
                'code' => 'AR',
                'type' => 'League',
                'is_active' => true,
            ],

            // International Competitions
            [
                'api_id' => 2,
                'name' => 'UEFA Champions League',
                'country' => 'World',
                'code' => 'EU',
                'type' => 'Cup',
                'is_active' => true,
            ],
            [
                'api_id' => 3,
                'name' => 'UEFA Europa League',
                'country' => 'World',
                'code' => 'EU',
                'type' => 'Cup',
                'is_active' => true,
            ],
        ];

        foreach ($leagues as $league) {
            // Store the api_id as the actual id
            $leagueData = $league;
            $leagueData['id'] = $league['api_id'];
            unset($leagueData['api_id']);
            
            League::updateOrCreate(
                ['id' => $leagueData['id']],
                $leagueData
            );
        }
    }
}