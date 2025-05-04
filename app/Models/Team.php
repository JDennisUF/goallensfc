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

    public function usersWhoFavorited()
    {
        return $this->belongsToMany(User::class, 'favorite_team_user');
    }
    public function leagues()
    {
        return $this->belongsToMany(League::class, 'league_team', 'team_id', 'league_id')
            ->withTimestamps();
    }

}
