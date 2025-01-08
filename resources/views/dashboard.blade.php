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
                <div class="playlist-list-container mt-8 flex justify-start gap-4 flex-wrap p-4">
                    <div id="playlist-list" class="playlist-list flex space-x-4"></div>
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

    <!-- Scripts -->
    <script src="{{ asset('face-api.min.js') }}"></script>
    <script src="{{ asset('scripts.js') }}"></script>
</x-app-layout>
