<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteTeamUser extends Model
{
    protected $table = 'favorite_team_user';

    protected $fillable = [
        'user_id',
        'team_id',
        'league_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function league()
    {
        return $this->belongsTo(League::class, 'league_id');
    }
}
