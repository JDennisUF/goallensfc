<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $table = 'leagues';

    protected $fillable = [
        'id',
        'name',
        'logo_url',
        'country',
        'season',
        'type',
        'is_active'
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'league_team', 'league_id', 'team_id')
            ->withTimestamps();
    }
    public function favoriteTeamUsers()
    {
        return $this->hasMany(FavoriteTeamUser::class);
    }
}
