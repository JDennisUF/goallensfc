<!-- filepath: resources/views/teams.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Teams</title>
</head>

<body>
    <h1>Teams</h1>
    <ul>
        @forelse ($teams as $team)
            <li>
                @if (isset($team['logo']))
                    <img src="{{ $team['logo_url'] }}" alt="{{ $team['name'] ?? 'League Logo' }}"
                        style="width: 50px; height: 50px;" onerror="this.onerror=null; this.src='/images/deflatedball.png';">
                @endif
                {{ $team['name'] ?? 'Unnamed Team' }} id: {{ $team['id'] }}
            </li>
        @empty
            <li>No teams available.</li>
        @endforelse
    </ul>
</body>

</html>