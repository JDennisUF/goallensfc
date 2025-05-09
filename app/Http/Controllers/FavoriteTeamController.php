<?php

namespace App\Http\Controllers;

use App\Helpers\LogoHelper;
use App\Models\FavoriteTeamUser;
use App\Models\League;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FavoriteTeamController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all active leagues
        $leagues = League::where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        // Fetch teams for the selected league
        $teams = new Collection();
        $favoriteTeamIds = [];
        if ($request->filled('league_id')) {
            $teams = League::find($request->league_id)->teams->sortBy('name') ?? [];
            $teams = LogoHelper::addTeamLogos($teams->toArray());

            // Fetch user's favorite team IDs
            $favoriteTeamIds = FavoriteTeamUser::where('user_id', auth()->id())
                ->pluck('team_id')
                ->toArray();
        }

        return view('favorites.index', compact('leagues', 'teams', 'favoriteTeamIds'));
    }

    public function store(Request $request)
    {
        $userId = auth()->id();
        logger('store request', $request->all());
        $leagueId = $request->input('league_id');
        logger('leagueId' . $leagueId);

        // Sync the user's favorite teams with the selected teams
        $selectedTeamIds = $request->input('favorites', []);

        foreach ($selectedTeamIds as $teamId) {
            FavoriteTeamUser::create([
                'user_id' => $userId,
                'team_id' => $teamId,
                'league_id' => $leagueId,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Favorites updated successfully!']);
    }

    public function destroy($teamId, $leagueId)
    {
        logger('destroy request', ['teamId' => $teamId]);
        logger('leagueId' . $leagueId);
        $userId = auth()->id();

        // Delete the favorite_team_user record
        FavoriteTeamUser::where('user_id', $userId)
            ->where('team_id', $teamId)
            ->where('league_id', $leagueId)
            ->delete();

        return response()->json(['success' => true]);
    }
}