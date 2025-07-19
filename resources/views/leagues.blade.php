<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-green-800">
            🏆 Football Leagues
        </h2>
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-md">
        @if(!empty($leagues))
            <div class="mb-6">
                <p class="text-gray-600">Explore football leagues from around the world. Click on a league to view its teams.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($leagues as $league)
                    <a href="{{ route('teams', ['league_id' => $league['id'] ?? $league['api_id'] ?? '']) }}" 
                       class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition duration-300 bg-gray-50 hover:bg-white group">
                        <div class="flex flex-col items-center text-center">
                            @if(isset($league['logo_url']))
                                <img src="{{ $league['logo_url'] }}" alt="{{ $league['name'] ?? 'League Logo' }}" 
                                     class="w-16 h-16 mb-3 rounded-lg"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='/images/deflatedball.png';">
                            @else
                                <div class="w-16 h-16 mb-3 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl">⚽</span>
                                </div>
                            @endif
                            
                            <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-green-600 transition duration-300">
                                {{ $league['name'] ?? 'Unnamed League' }}
                            </h3>
                            
                            @if(isset($league['country']) && is_array($league['country']))
                                <p class="text-sm text-gray-500 mb-2">{{ $league['country']['name'] ?? 'Unknown Country' }}</p>
                            @elseif(isset($league['country']) && is_string($league['country']))
                                <p class="text-sm text-gray-500 mb-2">{{ $league['country'] }}</p>
                            @endif
                            
                            @if(isset($league['season']))
                                <span class="text-xs text-gray-400">Season: {{ $league['season'] }}</span>
                            @endif
                            
                            @if(isset($league['type']))
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full mt-2">
                                    {{ ucfirst($league['type']) }}
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🏆</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Leagues Available</h3>
                <p class="text-gray-600 mb-4">There are no football leagues to display at the moment.</p>
                <p class="text-sm text-gray-500">Leagues may need to be fetched from the API first.</p>
            </div>
        @endif
    </div>

    @if(!empty($leagues))
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="text-blue-500 mr-3">
                    <span class="text-lg">💡</span>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-blue-800">Pro Tip</h4>
                    <p class="text-sm text-blue-700">Click on any league to view its teams and add them to your favorites!</p>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>