@props([
    'fixtureId',
    'title' => null
])

<div class="bg-white p-4 rounded-lg shadow-md">
    @if($title)
        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $title }}</h3>
    @endif
    
    <div id="wg-api-football-game-{{ $fixtureId }}" 
         data-host="v3.football.api-sports.io" 
         data-key="{{ env('FOOTBALL_API_KEY') }}"
         data-id="{{ $fixtureId }}"
         data-theme=""
         data-refresh="0"
         data-show-toolbar="true"
         data-show-errors="false"
         data-show-logos="true"
         data-modal-game="true"
         data-modal-show-logos="true">
    </div>
</div>

@push('scripts')
<script type="module" src="https://widgets.api-sports.io/2.0.3/widgets.js"></script>
@endpush