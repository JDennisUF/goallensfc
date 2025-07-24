<?php

namespace App\Services;

use App\Models\CachedMatch;
use App\Models\CachedTeamRecord;
use App\Models\CachedLeague;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CacheService
{
    protected $apiFootballService;

    public function __construct(ApiFootballService $apiFootballService)
    {
        $this->apiFootballService = $apiFootballService;
    }

    /**
     * Get matches for a team with smart caching
     * Returns cached data first, then fetches missing/new data from API
     */
    public function getTeamMatches($teamId, $season = null, $limit = 10)
    {
        $season = $season ?? now()->year;
        
        // Get cached matches
        $cachedMatches = CachedMatch::forTeam($teamId, $limit);
        
        // If we have enough cached matches, return them
        if ($cachedMatches->count() >= $limit) {
            return $cachedMatches->map->toApiFormat();
        }
        
        // Otherwise, try to fetch new matches from API
        $this->fetchAndCacheNewMatches($teamId, $season);
        
        // Return fresh cached data
        $freshMatches = CachedMatch::forTeam($teamId, $limit);
        return $freshMatches->map->toApiFormat();
    }

    /**
     * Get matches for multiple teams efficiently
     */
    public function getMultipleTeamMatches(array $teamIds, $limit = 10)
    {
        // Get all cached matches for these teams
        $cachedMatches = CachedMatch::forTeams($teamIds, $limit * count($teamIds));
        
        // Group by team
        $matchesByTeam = $cachedMatches->groupBy(function ($match) {
            return $match->home_team_id;
        })->merge(
            $cachedMatches->groupBy(function ($match) {
                return $match->away_team_id;
            })
        );
        
        // Check which teams need more data
        $teamsNeedingData = [];
        foreach ($teamIds as $teamId) {
            $teamMatches = $matchesByTeam->get($teamId, collect());
            if ($teamMatches->count() < 3) { // If less than 3 matches cached
                $teamsNeedingData[] = $teamId;
            }
        }
        
        // Fetch data for teams that need it (but limit API calls to 5 teams max)
        if (!empty($teamsNeedingData) && count($teamsNeedingData) <= 5) {
            foreach (array_slice($teamsNeedingData, 0, 5) as $teamId) {
                $this->fetchAndCacheNewMatches($teamId, now()->year);
            }
        }
        
        // Return all matches
        return CachedMatch::forTeams($teamIds, $limit)->map->toApiFormat();
    }

    /**
     * Get team record with caching
     */
    public function getTeamRecord($teamId, $leagueId, $season = null)
    {
        $season = $season ?? now()->year;
        
        // Try to get fresh cached record
        $cachedRecord = CachedTeamRecord::getFresh($teamId, $leagueId, $season);
        
        if ($cachedRecord) {
            return ['response' => $cachedRecord->toApiFormat()];
        }
        
        // Fetch from API and cache
        try {
            $apiResponse = $this->apiFootballService->getTeamRecord($teamId, $leagueId);
            
            if ($apiResponse && isset($apiResponse['response'])) {
                CachedTeamRecord::updateFromApiData($teamId, $leagueId, $apiResponse['response'], $season);
                return $apiResponse;
            }
        } catch (\Exception $e) {
            Log::error("Error fetching team record for team {$teamId}: " . $e->getMessage());
        }
        
        return ['response' => []];
    }

    /**
     * Fetch and cache new matches for a team
     */
    protected function fetchAndCacheNewMatches($teamId, $season)
    {
        try {
            // Get the latest match date we have cached
            $latestDate = CachedMatch::getLatestMatchDate($teamId);
            
            // Fetch matches from API
            $apiResponse = $this->apiFootballService->getTeamMatches($teamId, $season, 20);
            
            if (!$apiResponse || !isset($apiResponse['response'])) {
                return;
            }
            
            $newMatchesCount = 0;
            
            foreach ($apiResponse['response'] as $matchData) {
                $fixtureId = $matchData['fixture']['id'];
                
                // Skip if we already have this match
                if (CachedMatch::where('fixture_id', $fixtureId)->exists()) {
                    continue;
                }
                
                // Cache the new match
                CachedMatch::createFromApiData($matchData);
                $newMatchesCount++;
            }
            
            Log::info("Cached {$newMatchesCount} new matches for team {$teamId}");
            
        } catch (\Exception $e) {
            Log::error("Error fetching matches for team {$teamId}: " . $e->getMessage());
        }
    }

    /**
     * Warm up cache for favorite teams
     */
    public function warmupFavoriteTeams($user)
    {
        $favoriteTeamIds = $user->favoriteTeams->pluck('id')->toArray();
        
        if (empty($favoriteTeamIds)) {
            return;
        }
        
        Log::info("Warming up cache for " . count($favoriteTeamIds) . " favorite teams");
        
        // Limit to first 3 teams to avoid API rate limits
        foreach (array_slice($favoriteTeamIds, 0, 3) as $teamId) {
            $this->fetchAndCacheNewMatches($teamId, now()->year);
        }
    }

    /**
     * Clear expired cache entries
     */
    public function clearExpiredCache()
    {
        $expiredRecords = CachedTeamRecord::where('expires_at', '<', now())->count();
        CachedTeamRecord::where('expires_at', '<', now())->delete();
        
        Log::info("Cleared {$expiredRecords} expired team records");
    }

    /**
     * Get leagues with smart caching
     */
    public function getLeagues()
    {
        // Get cached leagues
        $cachedLeagues = CachedLeague::getFresh();
        
        // If we have fresh cached leagues, return them
        if ($cachedLeagues->isNotEmpty()) {
            return $cachedLeagues->map->toApiFormat();
        }
        
        // Otherwise, fetch from API and cache
        return $this->fetchAndCacheLeagues();
    }

    /**
     * Fetch and cache leagues from API
     */
    protected function fetchAndCacheLeagues()
    {
        try {
            // Fetch leagues from API
            $apiResponse = $this->apiFootballService->getLeagues();
            
            if (!$apiResponse || !isset($apiResponse['response'])) {
                return collect();
            }
            
            $cachedCount = 0;
            
            foreach ($apiResponse['response'] as $leagueData) {
                CachedLeague::createFromApiData($leagueData);
                $cachedCount++;
            }
            
            Log::info("Cached {$cachedCount} leagues from API");
            
            // Return fresh cached data
            return CachedLeague::getFresh()->map->toApiFormat();
            
        } catch (\Exception $e) {
            Log::error("Error fetching leagues: " . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get cache statistics
     */
    public function getCacheStats()
    {
        return [
            'total_matches' => CachedMatch::count(),
            'total_team_records' => CachedTeamRecord::count(),
            'total_leagues' => CachedLeague::count(),
            'fresh_team_records' => CachedTeamRecord::where('expires_at', '>', now())->count(),
            'fresh_leagues' => CachedLeague::where('expires_at', '>', now())->count(),
            'oldest_match' => CachedMatch::min('match_date'),
            'newest_match' => CachedMatch::max('match_date'),
        ];
    }
}