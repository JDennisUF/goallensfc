<!-- filepath: /resources/views/favorites/results.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-green-800">⭐ My Favorite Teams</h2>
    </x-slot>

    <div class="p-6 bg-white rounded-lg shadow-md text-gray-800">
        @if ($favoriteTeams->isEmpty())
            <p class="text-gray-600">You have no favorite teams yet.</p>
        @else
            <ul class="space-y-6">
                @foreach ($favoriteTeams as $team)
                    <li>
                        <div class="flex items-center space-x-4">
                            <!-- Team Logo -->
                            <img src="{{ $team['logo_url'] }}" alt="{{ $team['name'] }} Logo" class="border border-gray-300 p-2"
                                onerror="this.onerror=null; this.src='/images/deflatedball.png';">
                            <!-- Team Name and Record -->
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $team['name'] }}</h3>
                                <p class="text-gray-600">
                                    {{ $team['record']['fixtures']['wins']['total'] ?? 0 }}W -
                                    {{ $team['record']['fixtures']['draws']['total'] ?? 0 }}D -
                                    {{ $team['record']['fixtures']['loses']['total'] ?? 0 }}L
                                </p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-app-layout>