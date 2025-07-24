<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CachedTeamRecord extends Model
{
    protected $fillable = [
        'team_id',
        'league_id',
        'season',
        'wins',
        'draws',
        'losses',
        'goals_for',
        'goals_against',
        'matches_played',
        'last_updated',
        'expires_at',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Check if this record has expired and needs refreshing
     */
    public function isExpired()
    {
        return $this->expires_at < now();
    }

    /**
     * Get fresh record for team/league combination, or null if expired/missing
     */
    public static function getFresh($teamId, $leagueId, $season = null)
    {
        $season = $season ?? now()->year;
        
        $record = static::where('team_id', $teamId)
            ->where('league_id', $leagueId)
            ->where('season', $season)
            ->first();

        return ($record && !$record->isExpired()) ? $record : null;
    }

    /**
     * Create or update team record with API data
     */
    public static function updateFromApiData($teamId, $leagueId, $apiData, $season = null)
    {
        $season = $season ?? now()->year;
        
        return static::updateOrCreate(
            [
                'team_id' => $teamId,
                'league_id' => $leagueId,
                'season' => $season,
            ],
            [
                'wins' => $apiData['fixtures']['wins']['total'] ?? 0,
                'draws' => $apiData['fixtures']['draws']['total'] ?? 0,
                'losses' => $apiData['fixtures']['loses']['total'] ?? 0,
                'goals_for' => $apiData['goals']['for']['total']['total'] ?? 0,
                'goals_against' => $apiData['goals']['against']['total']['total'] ?? 0,
                'matches_played' => $apiData['fixtures']['played']['total'] ?? 0,
                'last_updated' => now(),
                'expires_at' => now()->addHours(6), // Cache for 6 hours
            ]
        );
    }

    /**
     * Convert to API response format for compatibility
     */
    public function toApiFormat()
    {
        return [
            'fixtures' => [
                'wins' => ['total' => $this->wins],
                'draws' => ['total' => $this->draws],
                'loses' => ['total' => $this->losses],
                'played' => ['total' => $this->matches_played],
            ],
            'goals' => [
                'for' => ['total' => ['total' => $this->goals_for]],
                'against' => ['total' => ['total' => $this->goals_against]],
            ]
        ];
    }

    /**
     * Get goal difference
     */
    public function getGoalDifferenceAttribute()
    {
        return $this->goals_for - $this->goals_against;
    }

    /**
     * Get points (assuming 3 for win, 1 for draw)
     */
    public function getPointsAttribute()
    {
        return ($this->wins * 3) + $this->draws;
    }
}