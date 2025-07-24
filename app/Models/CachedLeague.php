<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CachedLeague extends Model
{
    protected $fillable = [
        'league_id',
        'name',
        'type',
        'logo_url',
        'country',
        'country_code',
        'season',
        'current',
        'seasons',
        'cached_at',
        'expires_at'
    ];

    protected $casts = [
        'seasons' => 'array',
        'current' => 'boolean',
        'cached_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    public static function createFromApiData($leagueData)
    {
        $league = $leagueData['league'] ?? $leagueData;
        
        return self::updateOrCreate(
            ['league_id' => $league['id']],
            [
                'name' => $league['name'],
                'type' => $league['type'] ?? 'league',
                'logo_url' => $league['logo'] ?? null,
                'country' => $leagueData['country']['name'] ?? 'Unknown',
                'country_code' => $leagueData['country']['code'] ?? null,
                'season' => $leagueData['seasons'][0]['year'] ?? null,
                'current' => $leagueData['seasons'][0]['current'] ?? false,
                'seasons' => $leagueData['seasons'] ?? [],
                'cached_at' => now(),
                'expires_at' => now()->addDays(7) // Cache for 1 week
            ]
        );
    }

    public function toApiFormat()
    {
        return [
            'league' => [
                'id' => $this->league_id,
                'name' => $this->name,
                'type' => $this->type,
                'logo' => $this->logo_url
            ],
            'country' => [
                'name' => $this->country,
                'code' => $this->country_code
            ],
            'seasons' => $this->seasons ?? []
        ];
    }

    public static function getFresh($limit = null)
    {
        $query = self::where('expires_at', '>', now())
                    ->orderBy('country')
                    ->orderBy('name');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }

    public static function clearExpired()
    {
        return self::where('expires_at', '<', now())->delete();
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeCurrent($query)
    {
        return $query->where('current', true);
    }
}
