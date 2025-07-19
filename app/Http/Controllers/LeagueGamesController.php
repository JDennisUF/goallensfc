<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeagueGamesController extends Controller
{
    /**
     * Display the league games view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('league_games');
    }
}