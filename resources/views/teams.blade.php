<!-- filepath: resources/views/teams.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Teams</title>
</head>

<body>
    <h1>Teams</h1>
    <ul>
        <li>Team Count: {{ count($teams) }}</li>
        @forelse ($teams as $team)
            <li>
                <img src="https://media.api-sports.io/football/teams/{{ $team['team']['id'] }}.png"
                    alt="{{ $team['team']['name'] ?? 'League Logo' }}" style="width: 50px; height: 50px;"
                    onerror="this.onerror=null; this.src='/images/deflatedball.png';">
                {{ $team['team']['name'] ?? 'Unnamed Team' }} id: {{ $team['team']['id'] }}
            </li>
        @empty
            <li>No teams available.</li>
        @endforelse
    </ul>
</body>

</html>