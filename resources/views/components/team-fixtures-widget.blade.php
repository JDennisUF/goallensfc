@props([
    'team',
    'teamName' => null,
    'season' => date('Y')
])

<x-api-football-widget 
    :widget-id="'wg-fixtures-team-' . $team"
    type="fixtures"
    :team="$team"
    :season="$season"
    :title="$teamName ? $teamName . ' Fixtures' : 'Team Fixtures'"
    :show-toolbar="false"
    :show-logos="true"
    :modal-game="true"
    :modal-show-logos="true"
/>