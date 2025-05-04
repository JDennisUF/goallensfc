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
        $leagues = League::where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
        logger('request', $request->all());

        // Fetch teams for the selected league
        $teams = [];
        if ($request->has('league_id')) {
            $teams = League::find($request->league_id)->teams->sortBy('name') ?? [];
        }

        // Fetch user's favorite team IDs
        $favoriteTeamIds = FavoriteTeamUser::where('user_id', auth()->id())
            ->pluck('team_id')
            ->toArray();

        return view('favorites.index', compact('leagues', 'teams', 'favoriteTeamIds'));
    }

    public function store(Request $request)
    {
        $userId = auth()->id();
        logger('request', $request->all());

        // Sync the user's favorite teams with the selected teams
        $selectedTeamIds = $request->input('favorites', []);
        foreach ($selectedTeamIds as $teamId) {
            FavoriteTeamUser::create([
                'user_id' => $userId,
                'team_id' => $teamId,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Favorites updated successfully!']);
    }

    public function destroy($teamId)
    {
        $userId = auth()->id();

        // Delete the favorite_team_user record
        FavoriteTeamUser::where('user_id', $userId)
            ->where('team_id', $teamId)
            ->delete();

        return response()->json(['success' => true]);
    }
}