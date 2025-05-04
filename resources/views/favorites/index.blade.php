<!-- filepath: /home/jasondennis/code/goallensfc/resources/views/favorites/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-green-800">⭐ Favorite Teams</h2>
    </x-slot>

    <div class="p-6 bg-white rounded shadow text-gray-800">
        <!-- League Selector -->
        <form method="GET" action="{{ url('/favorites') }}" class="mb-4">
            <label for="league" class="mr-2">Select League:</label>
            <select name="league_id" id="league" class="border p-2 rounded" onchange="this.form.submit()">
                <option value="">-- Select a League --</option>
                {{ logger($leagues->count()) }}
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

                <ul class="list-disc pl-6">
                    @foreach ($teams as $team)
                        <li class="mb-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="favorites[]" value="{{ $team->id }}"
                                    {{ in_array($team->id, $favoriteTeamIds) ? 'checked' : '' }}
                                    onchange="this.form.submit()" class="mr-2">
                                <img src="{{ $team->local_logo ?? $team->logo }}" alt="{{ $team->name }}" class="w-8 h-8 mr-2">
                                {{ $team->name }}
                            </label>
                        </li>
                    @endforeach
                </ul>
            </form>
        @else
            <p>No teams available for the selected league.</p>
        @endif
    </div>
</x-app-layout>