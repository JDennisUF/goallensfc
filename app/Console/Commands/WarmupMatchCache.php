<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CacheService;
use App\Models\Team;
use App\Models\User;

class WarmupMatchCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warmup-matches 
                            {--team-id= : Specific team ID to warm up}
                            {--user-id= : Warm up cache for specific user\'s favorite teams}
                            {--popular : Warm up cache for most popular teams}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm up the match cache with historical data';

    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        parent::__construct();
        $this->cacheService = $cacheService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting match cache warmup...');

        if ($this->option('team-id')) {
            $this->warmupSpecificTeam($this->option('team-id'));
        } elseif ($this->option('user-id')) {
            $this->warmupUserFavorites($this->option('user-id'));
        } elseif ($this->option('popular')) {
            $this->warmupPopularTeams();
        } else {
            $this->warmupAllUserFavorites();
        }

        // Show cache statistics
        $stats = $this->cacheService->getCacheStats();
        $this->info("Cache warmup completed!");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Matches Cached', $stats['total_matches']],
                ['Total Team Records', $stats['total_team_records']],
                ['Fresh Team Records', $stats['fresh_team_records']],
                ['Oldest Match', $stats['oldest_match']],
                ['Newest Match', $stats['newest_match']],
            ]
        );
    }

    protected function warmupSpecificTeam($teamId)
    {
        $this->info("Warming up cache for team ID: {$teamId}");
        
        $team = Team::find($teamId);
        if (!$team) {
            $this->error("Team with ID {$teamId} not found.");
            return;
        }

        $this->info("Fetching matches for {$team->name}...");
        $matches = $this->cacheService->getTeamMatches($teamId, now()->year, 20);
        
        $this->info("Cached " . count($matches) . " matches for {$team->name}");
    }

    protected function warmupUserFavorites($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return;
        }

        $this->info("Warming up cache for user: {$user->name}");
        $this->cacheService->warmupFavoriteTeams($user);
    }

    protected function warmupPopularTeams()
    {
        $this->info("Warming up cache for popular teams...");
        
        // Get teams from major leagues
        $popularTeamIds = [
            // Premier League popular teams
            33, 34, 35, 40, 42, 47, 49, 50, // Arsenal, Newcastle, Tottenham, Liverpool, Man City, Brighton, Chelsea, Man United
            
            // La Liga popular teams  
            529, 530, 531, 548, // Barcelona, Atletico Madrid, Athletic Bilbao, Real Madrid
            
            // MLS popular teams
            1609, 1610, 1613, 1616, // LAFC, LA Galaxy, Inter Miami, Atlanta United
        ];

        $bar = $this->output->createProgressBar(count($popularTeamIds));
        $bar->start();

        foreach ($popularTeamIds as $teamId) {
            $this->cacheService->getTeamMatches($teamId, now()->year, 15);
            $bar->advance();
            usleep(500000); // 0.5 second delay to avoid rate limiting
        }

        $bar->finish();
        $this->newLine();
        $this->info("Warmed up cache for " . count($popularTeamIds) . " popular teams");
    }

    protected function warmupAllUserFavorites()
    {
        $this->info("Warming up cache for all users' favorite teams...");
        
        $users = User::has('favoriteTeams')->get();
        
        if ($users->isEmpty()) {
            $this->warn("No users with favorite teams found.");
            return;
        }

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            $this->cacheService->warmupFavoriteTeams($user);
            $bar->advance();
            sleep(1); // 1 second delay between users to avoid rate limiting
        }

        $bar->finish();
        $this->newLine();
        $this->info("Warmed up cache for {$users->count()} users");
    }
}
