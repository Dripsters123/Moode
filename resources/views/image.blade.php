<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Emotion-Based Playlist Finder') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <!-- Image Upload Section -->
                <div class="mt-8 flex justify-center">
                    <input type="file" id="image-upload" accept="image/*" />
                </div>

                <!-- Read Button Section -->
                <div class="mt-8 flex justify-center">
                    <button id="read-button" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-colors">
                        Read Expression
                    </button>
                </div>

                <!-- Mood Input Section (Displays Detected Mood) -->
                <div class="mt-8 flex justify-center">
                    <input type="text" id="mood-input" disabled placeholder="Detected Mood will appear here">
                </div>

                <!-- Playlist Section -->
                <div class="playlist-list-container mt-8 flex justify-start gap-4 flex-wrap p-4 overflow-x-auto">
                    <div id="playlist-list" class="playlist-list flex space-x-4">
                        <!-- Playlist items will dynamically populate here -->
                    </div>
                </div>

                <!-- Playlist Detail Section -->
                <div id="playlist-detail-container" class="hidden mt-8">
                    <button id="back-button" class="bg-blue-500 text-white py-2 px-4 rounded-md mb-4 hover:bg-blue-600 transition-colors">
                        Back to Playlists
                    </button>
                    <div id="playlist-detail" class="playlist-detail">
                        <!-- Playlist detail content will go here -->
                    </div>
                </div>

                <!-- Button Section -->
                <div class="mt-4">
                    <button type="button" id="btn-submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-colors">
                        Get Playlists
                    </button>
                </div>

                <!-- Hidden Token -->
                <input type="hidden" id="hidden_token">
            </div>
        </div>
    </div>

    <!-- Styles -->
    <style>
        /* Styles for Playlist and Image Upload */
        .playlist-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 50%;
            width: 150px;
            height: 150px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            overflow: hidden;
        }
        .playlist-item img {
            border-radius: 50%;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .playlist-list-container {
            display: flex;
            justify-content: start;
            gap: 16px;
            overflow-x: auto;
        }
        .playlist-detail {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }
    </style>

    <!-- Scripts -->
    <script src="{{ asset('face-api.min.js') }}"></script>
    <script src="{{ asset('image.js') }}"></script>
</x-app-layout>
