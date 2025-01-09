<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Emotion-Based Playlist Finder') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <!-- Video Section -->
                <div id="container" class="mt-8 flex justify-center">
                    <video id="video-feed" height="560" width="720" autoplay class="rounded-lg shadow-md"></video>
                    <canvas id="canvas" class="hidden"></canvas>
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

                <!-- Button Section (moved below the video) -->
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
        /* Playlist items */
        .playlist-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 50%; /* Makes the container circular */
            width: 150px;
            height: 150px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            overflow: hidden; /* Ensures content stays within the rounded shape */
        }

        .playlist-item img {
            border-radius: 50%; /* Makes the image circular */
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensures image scales properly within the circle */
        }

        .playlist-item > *:not(img) {
            display: none;
        }

        /* Hover animation */
        .playlist-item:hover {
            transform: scale(1.1); /* Slightly enlarges the playlist */
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2); /* Adds a more prominent shadow */
        }

        /* Horizontal scroll for playlist container */
        .playlist-list-container {
            display: flex;
            justify-content: start;
            gap: 16px; /* Space between the items */
            overflow-x: auto;
        }

        /* Scrollbar customization */
        .playlist-list-container::-webkit-scrollbar {
            height: 8px;
        }

        .playlist-list-container::-webkit-scrollbar-thumb {
            background-color: #007bff; /* Blue color */
            border-radius: 4px;
        }

        .playlist-list-container::-webkit-scrollbar-track {
            background-color: transparent;
        }

        /* Hide playlist details by default */
        .playlist-detail {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        #playlist-detail-container.hidden {
            display: none;
        }

        /* Back button */
        #back-button {
            width: auto;
            margin-bottom: 16px;
        }
    </style>

    <script src="{{ asset('face-api.min.js') }}"></script>
    <script src="{{ asset('scripts.js') }}"></script>
</x-app-layout>

