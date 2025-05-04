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
    ];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
    public function favoriteTeamUsers()
    {
        return $this->hasMany(FavoriteTeamUser::class);
    }
}
