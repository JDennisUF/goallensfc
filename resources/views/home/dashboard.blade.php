<x-app-layout>
    <div class="space-y-6">
        <!-- Quick Stats and Actions Combined -->
        <div class="bg-white p-3 rounded-lg shadow-md">
            <!-- Quick Stats Row - Force Horizontal with Flexbox -->
            <div class="flex justify-between items-center mb-3 px-2">
                <div class="text-center flex-1">
                    <div class="text-lg font-bold text-green-600">{{ $favoriteTeams->count() }}</div>
                    <div class="text-xs text-gray-600">Favorite Teams</div>
                </div>
                <div class="text-center flex-1">
                    <div class="text-lg font-bold text-blue-600">{{ $recentMatches->count() }}</div>
                    <div class="text-xs text-gray-600">Recent Matches</div>
                </div>
                <div class="text-center flex-1">
                    <div class="text-lg font-bold text-yellow-600">{{ $favoriteTeams->unique('pivot.league_id')->count() }}</div>
                    <div class="text-xs text-gray-600">Leagues Followed</div>
                </div>
            </div>

            <!-- Quick Actions Row - Force Horizontal with Flexbox -->
            <div class="flex gap-1">
                <a href="{{ url('/favorites') }}" class="bg-green-100 hover:bg-green-200 p-2 rounded text-center transition duration-300 flex-1">
                    <div class="text-sm mb-1">⭐</div>
                    <div class="text-xs font-medium">Favorites</div>
                </a>
                <a href="{{ url('/results') }}" class="bg-blue-100 hover:bg-blue-200 p-2 rounded text-center transition duration-300 flex-1">
                    <div class="text-sm mb-1">📊</div>
                    <div class="text-xs font-medium">Results</div>
                </a>
                <a href="{{ url('/leagues') }}" class="bg-yellow-100 hover:bg-yellow-200 p-2 rounded text-center transition duration-300 flex-1">
                    <div class="text-sm mb-1">🏆</div>
                    <div class="text-xs font-medium">Leagues</div>
                </a>
                <a href="{{ url('/teams') }}" class="bg-purple-100 hover:bg-purple-200 p-2 rounded text-center transition duration-300 flex-1">
                    <div class="text-sm mb-1">👥</div>
                    <div class="text-xs font-medium">Teams</div>
                </a>
            </div>
        </div>

        <!-- Recent Matches -->
        @if($recentMatches->isNotEmpty())
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Matches</h3>
                <a href="{{ url('/results') }}" class="text-green-600 hover:text-green-700 text-sm font-medium">View All Results</a>
            </div>
            <div class="space-y-2">
                @foreach($recentMatches->take(6) as $match)
                <div class="border border-gray-200 rounded-lg p-3">
                    <div class="grid grid-cols-12 gap-2 items-center">
                        <!-- Home Team (3 columns) -->
                        <div class="col-span-3 flex items-center justify-end space-x-2">
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900 truncate">{{ $match['teams']['home']['name'] ?? 'Home' }}</div>
                            </div>
                            <img src="{{ $match['teams']['home']['logo'] ?? '/images/deflatedball.png' }}" 
                                 alt="Home team" class="w-6 h-6 flex-shrink-0">
                        </div>
                        
                        <!-- Score (2 columns) -->
                        <div class="col-span-2 text-center">
                            <div class="font-bold text-lg">
                                {{ $match['goals']['home'] ?? '-' }} : {{ $match['goals']['away'] ?? '-' }}
                            </div>
                        </div>
                        
                        <!-- Away Team (3 columns) -->
                        <div class="col-span-3 flex items-center space-x-2">
                            <img src="{{ $match['teams']['away']['logo'] ?? '/images/deflatedball.png' }}" 
                                 alt="Away team" class="w-6 h-6 flex-shrink-0">
                            <div class="text-left">
                                <div class="text-sm font-medium text-gray-900 truncate">{{ $match['teams']['away']['name'] ?? 'Away' }}</div>
                            </div>
                        </div>
                        
                        <!-- Match Info (4 columns) -->
                        <div class="col-span-4 text-right">
                            <div class="text-sm text-gray-600">
                                {{ isset($match['fixture']['date']) ? \Carbon\Carbon::parse($match['fixture']['date'])->format('M j, Y') : 'TBD' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ isset($match['fixture']['status']['short']) && $match['fixture']['status']['short'] === 'FT' ? 'Final' : ($match['fixture']['status']['long'] ?? 'Scheduled') }}
                                • {{ $match['league']['name'] ?? 'League' }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Favorite Teams Overview -->
        @if($favoriteTeams->isNotEmpty())
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Your Favorite Teams</h3>
                <a href="{{ url('/favorites') }}" class="text-green-600 hover:text-green-700 text-sm font-medium">Manage All</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($favoriteTeams as $team)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-300">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $team->logo_url }}" alt="{{ $team->name }}" 
                             class="w-12 h-12 rounded" 
                             onerror="this.onerror=null; this.src='/images/deflatedball.png';">
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $team->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $team->country }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <div class="text-6xl mb-4">⚽</div>
            <h3 class="text-xl font-semibold mb-2">No Favorite Teams Yet</h3>
            <p class="text-gray-600 mb-4">Start by adding some teams to your favorites to see personalized content here.</p>
            <a href="{{ url('/favorites') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition duration-300">
                Add Favorite Teams
            </a>
        </div>
        @endif

        <!-- League Standings Widgets -->
        @if($favoriteLeagues->isNotEmpty())
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">League Standings</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($favoriteLeagues->take(4) as $league)
                    <x-league-standings-widget 
                        :league="$league->id"
                        :league-name="$league->name"
                    />
                @endforeach
            </div>
        </div>
        @endif
    </div>
</x-app-layout>