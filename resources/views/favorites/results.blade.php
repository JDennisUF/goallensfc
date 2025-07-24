<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-green-800">📊 Favorite Teams Results</h2>
    </x-slot>

    @if ($favoriteTeams->isEmpty())
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <div class="text-6xl mb-4">⚽</div>
            <h3 class="text-xl font-semibold mb-2">No Favorite Teams Yet</h3>
            <p class="text-gray-600 mb-4">Add some teams to your favorites to see their recent matches here.</p>
            <a href="{{ url('/favorites') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition duration-300">
                Add Favorite Teams
            </a>
        </div>
    @else
        <div class="space-y-6">
            @foreach ($favoriteTeams as $team)
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <!-- Team Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $team['logo_url'] }}" alt="{{ $team['name'] }} Logo" 
                                 class="w-16 h-16 rounded"
                                 onerror="this.onerror=null; this.src='/images/deflatedball.png';">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $team['name'] }}</h3>
                                <p class="text-base text-gray-600 font-medium">
                                    Record: {{ $team['record']['fixtures']['wins']['total'] ?? 0 }}W - 
                                    {{ $team['record']['fixtures']['draws']['total'] ?? 0 }}D - 
                                    {{ $team['record']['fixtures']['loses']['total'] ?? 0 }}L
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-base font-medium text-gray-600">{{ $team['matches']->count() ?? 0 }} Recent Matches</span>
                        </div>
                    </div>

                    <!-- Recent Matches -->
                    @if(isset($team['matches']) && $team['matches']->isNotEmpty())
                        <div class="space-y-1">
                            @foreach($team['matches']->take(8) as $match)
                                <!-- Debug: Show match data structure -->
                                @if($loop->first)
                                    <div class="bg-yellow-50 border border-yellow-200 rounded p-2 mb-2 text-xs">
                                        <strong>Debug - Match data keys:</strong> {{ implode(', ', array_keys($match)) }}
                                        @if(isset($match['fixture']))
                                            <br><strong>Fixture keys:</strong> {{ implode(', ', array_keys($match['fixture'])) }}
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="border border-gray-200 rounded-lg p-2 bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer match-row"
                                     data-fixture-id="{{ $match['fixture']['id'] ?? $match['id'] ?? '' }}"
                                     data-team-id="{{ $team['id'] }}"
                                     onclick="window.open('{{ route('game-widget', ['fixtureId' => $match['fixture']['id'] ?? $match['id'] ?? '']) }}', '_blank')"
                                     title="Match ID: {{ $match['fixture']['id'] ?? $match['id'] ?? 'No ID' }}">
                                    <div class="flex items-center justify-between gap-2">
                                        <!-- Home Team -->
                                        <div class="flex items-center justify-end space-x-2 flex-1 min-w-0">
                                            <div class="text-right truncate">
                                                <div class="text-sm font-semibold text-gray-900">{{ $match['teams']['home']['name'] ?? 'Home' }}</div>
                                            </div>
                                            <img src="{{ $match['teams']['home']['logo'] ?? '/images/deflatedball.png' }}" 
                                                 alt="Home team" class="w-6 h-6 flex-shrink-0">
                                        </div>
                                        
                                        <!-- Score -->
                                        <div class="text-center px-3">
                                            <div class="font-bold text-lg text-green-700 whitespace-nowrap">
                                                {{ $match['goals']['home'] ?? '-' }} : {{ $match['goals']['away'] ?? '-' }}
                                            </div>
                                        </div>
                                        
                                        <!-- Away Team -->
                                        <div class="flex items-center space-x-2 flex-1 min-w-0">
                                            <img src="{{ $match['teams']['away']['logo'] ?? '/images/deflatedball.png' }}" 
                                                 alt="Away team" class="w-6 h-6 flex-shrink-0">
                                            <div class="text-left truncate">
                                                <div class="text-sm font-semibold text-gray-900">{{ $match['teams']['away']['name'] ?? 'Away' }}</div>
                                            </div>
                                        </div>
                                        
                                        <!-- Match Info -->
                                        <div class="text-right flex-shrink-0">
                                            <div class="text-xs text-gray-600">
                                                {{ isset($match['fixture']['date']) ? \Carbon\Carbon::parse($match['fixture']['date'])->format('M j') : 'TBD' }}
                                                • {{ isset($match['fixture']['status']['short']) && $match['fixture']['status']['short'] === 'FT' ? 'Final' : ($match['fixture']['status']['short'] ?? 'Scheduled') }}
                                            </div>
                                            <div class="text-xs text-blue-600 font-medium">
                                                📊 Details
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <div class="text-4xl mb-2">⚽</div>
                            <p class="text-gray-600">No recent matches available for {{ $team['name'] }}</p>
                            <p class="text-sm text-gray-500 mt-1">Matches may be loading or unavailable from the API</p>
                        </div>
                    @endif

                    <!-- Team Fixtures Widget -->
                    <div class="mt-4">
                        <x-team-fixtures-widget 
                            :team="$team['id']"
                            :team-name="$team['name']"
                        />
                    </div>
                </div>
            @endforeach
        </div>

        @if($favoriteTeams->isNotEmpty())
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="text-blue-500 mr-3">
                        <span class="text-lg">💡</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-blue-800">About Results</h4>
                        <p class="text-sm text-blue-700">Recent matches are fetched live from API-Football. Results may take a moment to load.</p>
                    </div>
                </div>
            </div>
        @endif
    @endif

    @push('scripts')
    <script>
        // Add some visual feedback for clickable rows
        document.addEventListener('DOMContentLoaded', function() {
            const matchRows = document.querySelectorAll('.match-row');
            matchRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.01)';
                });
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
    @endpush
</x-app-layout>