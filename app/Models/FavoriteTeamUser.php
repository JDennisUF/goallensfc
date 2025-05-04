<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteTeamUser extends Model
{
    protected $table = 'favorite_team_user';

    protected $fillable = [
        'user_id',
        'team_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
