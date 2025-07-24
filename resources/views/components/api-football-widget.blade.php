@props([
    'widgetId' => 'wg-api-football-' . uniqid(),
    'type' => 'games',
    'league' => null,
    'team' => null,
    'season' => date('Y'),
    'date' => null,
    'theme' => '',
    'showToolbar' => true,
    'showErrors' => false,
    'showLogos' => true,
    'modalGame' => true,
    'modalStandings' => true,
    'modalShowLogos' => true,
    'refresh' => 0,
    'title' => null
])

<div class="bg-white p-4 rounded-lg shadow-md">
    @if($title)
        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $title }}</h3>
    @endif
    
    <div id="{{ $widgetId }}" 
         data-host="v3.football.api-sports.io" 
         data-key="{{ env('FOOTBALL_API_KEY') }}"
         @if($date) data-date="{{ $date }}" @endif
         @if($league) data-league="{{ $league }}" @endif
         @if($team) data-team="{{ $team }}" @endif
         data-season="{{ $season }}"
         data-theme="{{ $theme }}"
         data-refresh="{{ $refresh }}"
         data-show-toolbar="{{ $showToolbar ? 'true' : 'false' }}"
         data-show-errors="{{ $showErrors ? 'true' : 'false' }}"
         data-show-logos="{{ $showLogos ? 'true' : 'false' }}"
         data-modal-game="{{ $modalGame ? 'true' : 'false' }}"
         data-modal-standings="{{ $modalStandings ? 'true' : 'false' }}"
         data-modal-show-logos="{{ $modalShowLogos ? 'true' : 'false' }}">
    </div>
</div>

@push('scripts')
<script type="module" src="https://widgets.api-sports.io/2.0.3/widgets.js"></script>
@endpush