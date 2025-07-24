<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cached_matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fixture_id')->unique(); // API fixture ID
            $table->unsignedBigInteger('league_id'); // League ID
            $table->string('league_name'); // Cache league name for quick access
            
            // Home team data
            $table->unsignedBigInteger('home_team_id');
            $table->string('home_team_name');
            $table->string('home_team_logo')->nullable();
            $table->integer('home_goals')->nullable();
            
            // Away team data
            $table->unsignedBigInteger('away_team_id');
            $table->string('away_team_name');
            $table->string('away_team_logo')->nullable();
            $table->integer('away_goals')->nullable();
            
            // Match details
            $table->dateTime('match_date');
            $table->string('status', 20); // FT, LIVE, NS, etc.
            $table->string('status_long', 50)->nullable(); // Full status description
            $table->integer('season')->default(2025);
            
            // Cache metadata
            $table->timestamps();
            
            // Indexes for efficient querying
            $table->index(['home_team_id', 'match_date']);
            $table->index(['away_team_id', 'match_date']);
            $table->index(['league_id', 'match_date']);
            $table->index(['season', 'match_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cached_matches');
    }
};