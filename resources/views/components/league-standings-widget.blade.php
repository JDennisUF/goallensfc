@props([
    'league',
    'leagueName' => null,
    'season' => date('Y')
])

<x-api-football-widget 
    :widget-id="'wg-standings-league-' . $league"
    type="standings"
    :league="$league"
    :season="$season"
    :title="$leagueName ? $leagueName . ' Standings' : 'League Standings'"
    :show-toolbar="false"
    :show-logos="true"
    :modal-standings="true"
    :modal-show-logos="true"
/>