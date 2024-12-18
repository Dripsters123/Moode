<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Spotify Mood-Based Playlist Finder') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <!-- Camera and Snapshot Section -->
                <div class="mb-8">
                    <label class="block text-gray-700 text-lg font-medium mb-2">Capture Your Mood:</label>
                    <div class="flex flex-col items-center space-y-4">
                        <video id="video" autoplay playsinline class="hidden border rounded-md w-full max-w-md"></video>
                        <canvas id="snapshot" class="hidden"></canvas>
                        <button id="capture-btn" class="bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 transition-colors">
                            Capture Image
                        </button>
                        <p id="mood-detection-result" class="text-gray-600 text-lg mt-4"></p>
                    </div>
                </div>

                <!-- Form for Mood Category Selection -->
                <form id="category-form" class="space-y-6">
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

    <!-- Hidden Token Input Field for Storing Spotify Token -->
    <input type="hidden" id="hidden_token" value="">

    <script src="spotify.js"></script>
    <script src="camera.js"></script>
</x-app-layout>
