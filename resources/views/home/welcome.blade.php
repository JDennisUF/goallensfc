<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GoalLensFC - Track Your Favorite Soccer Teams</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-green-50 to-blue-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-green-600">⚽ GoalLensFC</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                        <a href="{{ route('register') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">Sign Up</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative bg-gradient-to-r from-green-600 to-blue-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6">
                        Track Your Favorite Soccer Teams
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
                        Follow leagues worldwide, get real-time match results, and never miss a goal from your favorite teams.
                    </p>
                    <div class="space-x-4">
                        <a href="{{ route('register') }}" class="bg-white text-green-600 hover:bg-gray-100 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                            Get Started Free
                        </a>
                        <a href="{{ url('/leagues') }}" class="border-2 border-white text-white hover:bg-white hover:text-green-600 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                            Browse Leagues
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Choose GoalLensFC?</h2>
                    <p class="text-lg text-gray-600">Everything you need to stay connected with football worldwide</p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center p-6">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">🏆</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Global Coverage</h3>
                        <p class="text-gray-600">Follow teams from Premier League, La Liga, Serie A, Bundesliga, and leagues worldwide.</p>
                    </div>
                    
                    <div class="text-center p-6">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">⚡</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Real-Time Updates</h3>
                        <p class="text-gray-600">Get live scores, match results, and team statistics as they happen.</p>
                    </div>
                    
                    <div class="text-center p-6">
                        <div class="bg-yellow-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">⭐</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Personalized Experience</h3>
                        <p class="text-gray-600">Create your favorites list and get a customized dashboard for your teams.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Leagues -->
        @if($featuredLeagues->isNotEmpty())
        <div class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Leagues</h2>
                    <p class="text-lg text-gray-600">Explore the world's top football competitions</p>
                </div>
                
                <div class="grid md:grid-cols-5 gap-6">
                    @foreach($featuredLeagues as $league)
                    <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition duration-300">
                        <img src="{{ $league->logo_url }}" alt="{{ $league->name }}" 
                             class="w-16 h-16 mx-auto mb-4" 
                             onerror="this.onerror=null; this.src='/images/deflatedball.png';">
                        <h3 class="font-semibold text-gray-900">{{ $league->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $league->country }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Call to Action -->
        <div class="bg-green-600 text-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
                <p class="text-xl mb-8">Join thousands of football fans tracking their favorite teams</p>
                <a href="{{ route('register') }}" class="bg-white text-green-600 hover:bg-gray-100 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                    Create Your Account
                </a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p>&copy; {{ date('Y') }} GoalLensFC. Built with Laravel & API-Football.</p>
            </div>
        </footer>
    </div>
</body>
</html>