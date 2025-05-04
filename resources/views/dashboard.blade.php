<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-green-800">
            ⚽ Welcome to GoalLensFC
        </h2>
    </x-slot>

    <div class="p-6 bg-white text-gray-800 rounded-lg shadow-md">
        <h3 class="text-2xl font-bold mb-4 text-green-700">Today's Match Highlights</h3>

        <p class="mb-2">Check out the latest scores, upcoming matches, and standings.</p>

        <div class="mt-6 space-y-4">
            <a href="{{ url('/') }}"
                class="inline-block px-4 py-2 bg-green-600 text-white font-semibold rounded hover:bg-green-700 transition">
                🏟 View Matches
            </a>

            <a href="{{ url('/teams') }}"
                class="inline-block px-4 py-2 bg-green-600 text-white font-semibold rounded hover:bg-green-700 transition">
                📋 Browse Teams
            </a>

            <a href="{{ url('/leagues') }}"
                class="inline-block px-4 py-2 bg-green-600 text-white font-semibold rounded hover:bg-green-700 transition">
                🏆 Explore Leagues
            </a>

            <a href="{{ url('/favorites') }}"
                class="inline-block px-4 py-2 bg-yellow-500 text-white font-semibold rounded hover:bg-yellow-600 transition">
                ⭐ Manage Favorite Teams
            </a>

        </div>
    </div>
</x-app-layout>