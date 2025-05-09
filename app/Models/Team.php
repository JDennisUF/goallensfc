<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'code',
        'country',
        'logo_url',
        'national',
        'founded'
    ];

    protected $appends = [
        'logo_url',
    ];

    public function usersWhoFavorited()
    {
        return $this->belongsToMany(User::class, 'favorite_team_user');
    }
    public function leagues()
    {
        return $this->belongsToMany(League::class, 'league_team', 'team_id', 'league_id')
            ->withTimestamps();
    }
    public function getLogoUrlAttribute($value)
    {
        // hacky, might not work in all flows.  Make sure
        // it does not interfere with storing logos from teams/leagues
        return 'http://127.0.0.1:8000/logos/teams/' . $this->id . '.png';
    }

}
