<!-- filepath: /home/jasondennis/code/goallensfc/resources/views/favorites/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-green-800">⭐ Select Your Favorite Teams</h2>
    </x-slot>

    <div class="p-6 bg-white rounded shadow text-gray-800">
        <!-- League Selector -->
        <form method="GET" action="{{ url('/favorites') }}" class="mb-4">
            <label for="league" class="mr-2">Select League:</label>
            <select name="league_id" id="league" class="border p-2 rounded" style="width: 240px;"
                onchange="this.form.submit()">
                <option value="">-- Select League --</option>
                @foreach ($leagues as $league)
                    <option value="{{ $league->id }}" {{ request('league_id') == $league->id ? 'selected' : '' }}>
                        {{ $league->name }}
                    </option>
                @endforeach
            </select>
        </form>

        <!-- Teams List -->
        @if ($teams)
            <form method="POST" action="{{ url('/favorites') }}">
                @csrf
                @method('POST')

                <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($teams as $team)
                        <li class="flex items-center space-x-4">
                            <!-- Favorite team checkbox -->
                            <input type="checkbox" name="favorites[]" value="{{ $team['id'] }}" 
                                {{ in_array($team['id'], $favoriteTeamIds) ? 'checked' : '' }} 
                                onchange="handleFavoriteChange(this, {{ $team['id'] }})"
                                class="w-6 h-6">
                            <!-- Team logo -->
                            <img src="{{ $team['logo_url'] }}" alt="Team Logo Missing"
                                class="w-12 h-12 rounded-lg" loading="lazy"
                                onerror="this.onerror=null; this.src='/images/deflatedball.png';">
                            <!-- Team name -->
                            <span class="text-lg font-bold text-gray-800">{{ $team['name'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </form>
        @else
            <p>No teams available for the selected league.</p>
        @endif
    </div>
    <script>
        let selectedLeagueId = document.getElementById('league').value;

        document.getElementById('league').addEventListener('change', function () {
            selectedLeagueId = this.value;
        });

        function handleFavoriteChange(checkbox, teamId) {
            const url = checkbox.checked ? '/favorites' : `/favorites/${teamId}-${selectedLeagueId}`;
            const method = checkbox.checked ? 'POST' : 'DELETE';

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    league_id: selectedLeagueId,
                    favorites: checkbox.checked ? [teamId] : null,
                }),
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to update favorites');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating your favorites.');
                });
        }
    </script>
</x-app-layout>