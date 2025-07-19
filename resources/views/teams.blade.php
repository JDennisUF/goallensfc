<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-green-800">
            👥 Browse Teams
        </h2>
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <!-- League Selector -->
        <div class="mb-6">
            <form method="GET" action="{{ route('teams') }}" class="flex items-center space-x-4">
                <label for="league_id" class="text-sm font-medium text-gray-700">Select League:</label>
                <select name="league_id" id="league_id" 
                        class="border border-gray-300 rounded-md px-3 py-2 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        onchange="this.form.submit()">
                    <option value="">-- Choose a League --</option>
                    @foreach($leagues as $league)
                        <option value="{{ $league->id }}" 
                                {{ request('league_id') == $league->id ? 'selected' : '' }}>
                            {{ $league->name }} ({{ $league->country }})
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if($selectedLeague)
            <div class="mb-4">
                <div class="flex items-center space-x-3">
                    <img src="{{ $selectedLeague->logo_url }}" alt="{{ $selectedLeague->name }}" 
                         class="w-12 h-12"
                         onerror="this.onerror=null; this.src='/images/deflatedball.png';">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $selectedLeague->name }}</h3>
                        <p class="text-gray-600">{{ $selectedLeague->country }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Teams Grid -->
        @if(!empty($teams))
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($teams as $team)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-300 bg-gray-50">
                        <div class="flex flex-col items-center text-center">
                            <img src="{{ $team['logo_url'] }}" alt="{{ $team['name'] ?? 'Team Logo' }}" 
                                 class="w-16 h-16 mb-3 rounded-lg"
                                 onerror="this.onerror=null; this.src='/images/deflatedball.png';">
                            <h4 class="font-semibold text-gray-900 mb-1">{{ $team['name'] ?? 'Unnamed Team' }}</h4>
                            @if(isset($team['country']))
                                <p class="text-sm text-gray-500 mb-2">{{ $team['country'] }}</p>
                            @endif
                            <span class="text-xs text-gray-400">ID: {{ $team['id'] }}</span>
                            @if(isset($team['founded']))
                                <span class="text-xs text-gray-400">Founded: {{ $team['founded'] }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif($selectedLeague)
            <div class="text-center py-12">
                <div class="text-6xl mb-4">⚽</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Teams Found</h3>
                <p class="text-gray-600 mb-4">No teams are available for {{ $selectedLeague->name }} yet.</p>
                <p class="text-sm text-gray-500">Teams may need to be fetched from the API first.</p>
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🏆</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Select a League</h3>
                <p class="text-gray-600">Choose a league from the dropdown above to view its teams.</p>
            </div>
        @endif
    </div>
</x-app-layout>