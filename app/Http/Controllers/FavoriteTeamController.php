<?php

namespace App\Http\Controllers;

use App\Models\FavoriteTeamUser;
use App\Models\League;
use Illuminate\Http\Request;

class FavoriteTeamController extends Controller
{
    public function index(Request $request)
    {
        // Later: Fetch leagues and teams from DB
        // For now, we will use the League model to fetch leagues
        // and the FavoriteTeamUser model to fetch user's favorite teams

        // Fetch all leagues
        $leagues = League::first();

        // Fetch teams for the selected league
        $teams = [];
        if ($request->has('league_id')) {
            logger('loading teams');
            $teams = League::find($request->league_id)->teams;
        }
        logger('leagues: ' . $leagues->count());

        // Fetch user's favorite team IDs
        $favoriteTeamIds = FavoriteTeamUser::where('user_id', auth()->id())
            ->pluck('team_id')
            ->toArray();

        logger('leagues: ', $leagues->toArray());


        return view('favorites.index', compact('leagues', 'teams', 'favoriteTeamIds'));
    }

    public function store(Request $request)
    {
        // Later: Save favorite team to DB
        return back()->with('success', 'Team added to favorites!');
    }

    public function destroy($id)
    {
        // Later: Remove team from user's favorites
        return back()->with('success', 'Team removed.');
    }
}