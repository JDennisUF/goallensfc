<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CachedMatch extends Model
{
    protected $fillable = [
        'fixture_id',
        'league_id',
        'league_name',
        'home_team_id',
        'home_team_name',
        'home_team_logo',
        'home_goals',
        'away_team_id',
        'away_team_name',
        'away_team_logo',
        'away_goals',
        'match_date',
        'status',
        'status_long',
        'season',
    ];

    protected $casts = [
        'match_date' => 'datetime',
        'home_goals' => 'integer',
        'away_goals' => 'integer',
    ];

    /**
     * Get matches for a specific team (home or away)
     */
    public static function forTeam($teamId, $limit = 10)
    {
        return static::where(function ($query) use ($teamId) {
            $query->where('home_team_id', $teamId)
                  ->orWhere('away_team_id', $teamId);
        })
        ->orderBy('match_date', 'desc')
        ->limit($limit)
        ->get();
    }

    /**
     * Get recent matches for multiple teams
     */
    public static function forTeams(array $teamIds, $limit = 10)
    {
        return static::where(function ($query) use ($teamIds) {
            $query->whereIn('home_team_id', $teamIds)
                  ->orWhereIn('away_team_id', $teamIds);
        })
        ->orderBy('match_date', 'desc')
        ->limit($limit)
        ->get();
    }

    /**
     * Get the latest match date for a team to know what we need to fetch
     */
    public static function getLatestMatchDate($teamId)
    {
        return static::where(function ($query) use ($teamId) {
            $query->where('home_team_id', $teamId)
                  ->orWhere('away_team_id', $teamId);
        })
        ->max('match_date');
    }

    /**
     * Convert to API response format for compatibility
     */
    public function toApiFormat()
    {
        return [
            'fixture' => [
                'id' => $this->fixture_id,
                'date' => $this->match_date->toISOString(),
                'status' => [
                    'short' => $this->status,
                    'long' => $this->status_long,
                ]
            ],
            'league' => [
                'id' => $this->league_id,
                'name' => $this->league_name,
            ],
            'teams' => [
                'home' => [
                    'id' => $this->home_team_id,
                    'name' => $this->home_team_name,
                    'logo' => $this->home_team_logo,
                ],
                'away' => [
                    'id' => $this->away_team_id,
                    'name' => $this->away_team_name,
                    'logo' => $this->away_team_logo,
                ]
            ],
            'goals' => [
                'home' => $this->home_goals,
                'away' => $this->away_goals,
            ]
        ];
    }

    /**
     * Create from API response data
     */
    public static function createFromApiData($matchData)
    {
        return static::create([
            'fixture_id' => $matchData['fixture']['id'],
            'league_id' => $matchData['league']['id'],
            'league_name' => $matchData['league']['name'],
            'home_team_id' => $matchData['teams']['home']['id'],
            'home_team_name' => $matchData['teams']['home']['name'],
            'home_team_logo' => $matchData['teams']['home']['logo'],
            'home_goals' => $matchData['goals']['home'],
            'away_team_id' => $matchData['teams']['away']['id'],
            'away_team_name' => $matchData['teams']['away']['name'],
            'away_team_logo' => $matchData['teams']['away']['logo'],
            'away_goals' => $matchData['goals']['away'],
            'match_date' => Carbon::parse($matchData['fixture']['date']),
            'status' => $matchData['fixture']['status']['short'],
            'status_long' => $matchData['fixture']['status']['long'],
            'season' => now()->year,
        ]);
    }
}