<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Details - Goal Lens FC</title>
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .header {
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            color: #059669;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 10px;
        }
        .back-link:hover {
            color: #047857;
        }
        #wg-api-football-game {
            min-height: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ url('/results') }}" class="back-link">
                ← Back to Results
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Match Details</h1>
            <p class="text-gray-600">Fixture ID: {{ $fixtureId }}</p>
        </div>

        <!-- API Football Game Widget -->
        <div id="wg-api-football-game" 
             data-host="v3.football.api-sports.io" 
             data-key="{{ env('FOOTBALL_API_KEY') }}"
             data-id="{{ $fixtureId }}"
             data-theme=""
             data-refresh="0"
             data-show-errors="false"
             data-show-logos="true">
        </div>
        
        <div class="mt-4 text-center">
            <button onclick="location.reload()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-medium">
                🔄 Reload Widget
            </button>
        </div>
    </div>

    <!-- API Football Widgets Script -->
    <script type="module" src="https://widgets.api-sports.io/2.0.3/widgets.js"></script>
</body>
</html>