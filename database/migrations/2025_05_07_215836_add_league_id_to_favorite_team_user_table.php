<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeagueIdToFavoriteTeamUserTable extends Migration
{
    public function up()
    {
        Schema::table('favorite_team_user', function (Blueprint $table) {
            $table->unsignedBigInteger('league_id')->nullable()->after('team_id');
            $table->foreign('league_id')->references('id')->on('leagues')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('favorite_team_user', function (Blueprint $table) {
            $table->dropForeign(['league_id']);
            $table->dropColumn('league_id');
        });
    }
}
