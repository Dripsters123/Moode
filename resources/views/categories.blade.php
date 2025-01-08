<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Spotify Mood-Based Playlist Finder') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <!-- Form -->
                <form class="space-y-6">
                    <input type="hidden" id="hidden_token">
                    <div class="mb-4">
                        <label for="select_mood" class="block text-gray-700 text-lg font-medium">Mood:</label>
                        <select id="select_mood" class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="select">Select...</option>
                            <option value="happy">Happy</option>
                            <option value="sad">Sad</option>
                            <option value="energetic">Energetic</option>
                            <option value="romantic">Romantic</option>
                            <option value="angry">Angry</option>
                            <option value="workout">Workout</option>
                            <option value="gaming">Gaming</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" id="btn_submit" class="w-full bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 transition-colors">Find Playlists</button>
                    </div>
                </form>

                <!-- Playlist Section -->
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 playlist-list-container">
                    <div class="playlist-list flex space-x-4"></div>
                </div>

                <!-- Playlist Detail Section -->
                <div id="playlist-detail" class="mt-8">
                    <button id="back-to-list" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-colors mb-4" style="display: none;">
                        Back to Playlist List
                    </button>
                </div>

               
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="spotify.js"></script>
</x-app-layout>
