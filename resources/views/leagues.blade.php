<!-- filepath: resources/views/leagues.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Leagues via API-Football</title>
</head>

<body>
    <h1>Leagues</h1>
    <ul>
        @forelse ($leagues as $league)
            <li>
                @if (isset($league['league']['logo']))
                    <img src="https://media.api-sports.io/football/leagues/{{ $league['league']['id'] }}.png"
                        alt="{{ $league['league']['name'] ?? 'League Logo' }}" style="width: 50px; height: 50px;" loading="lazy"
                        onerror="this.onerror=null; this.src='/images/deflatedball.png';">
                @endif
                {{ $league['league']['name'] ?? 'Unnamed League' }}
            </li>
        @empty
            <li>No competitions available.</li>
        @endforelse
    </ul>
</body>

</html>