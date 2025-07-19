<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $table = 'leagues';

    protected $fillable = [
        'name',
        'logo_url',
        'country',
        'code',
        'season',
        'type',
        'is_active'
    ];

    protected $appends = [
        'logo_url',
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
    public function getLogoUrlAttribute($value)
    {
        // hacky, might not work in all flows.  Make sure
        // it does not interfere with storing logos from teams/leagues
        return 'http://127.0.0.1:8000/logos/leagues/' . $this->id . '.png';
    }
}
