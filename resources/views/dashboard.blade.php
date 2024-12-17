<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Spotify Mood-Based Playlist Finder') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <!-- Form for Mood Category Selection -->
                <form id="category-form" class="space-y-6">
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

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="spotify.js"></script>
    <script>
        // Handle form submission for mood selection (by categories)
        const categoryForm = document.getElementById('category-form');
        const selectMood = document.getElementById('select_mood');

        categoryForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const mood = selectMood.value;

            if (mood !== 'select') {
                findPlaylistsByMood(mood);
            } else {
                alert("Please select a mood.");
            }
        });

        // Function to find playlists based on mood
        function findPlaylistsByMood(mood) {
            console.log("Searching playlists for mood: ", mood);

            // Call Spotify API or your server to fetch playlists based on mood
            // Placeholder: You can add the actual implementation to fetch playlists
        }
    </script>
    <style>
        /* Add a max height and enable scrolling for the playlist list container */
        .playlist-list-container {
            max-height: 400px; /* You can adjust the height as needed */
            overflow-y: auto; /* Enables vertical scrolling when content overflows */
            scroll-behavior: smooth; /* Smooth scrolling when using the wheel */
        }

        /* Optional: Adding a subtle scrollbar appearance */
        .playlist-list-container::-webkit-scrollbar {
            width: 8px;
        }

        .playlist-list-container::-webkit-scrollbar-thumb {
            background-color: #48BB78;
            border-radius: 10px;
        }

        .playlist-list-container::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Style for the playlist image */
        .playlist-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #ccc;
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        /* Hover effect for playlist image */
        .playlist-item:hover .playlist-image {
            transform: scale(1.05); /* Slight zoom effect */
            border-color: #48BB78; /* Green border on hover */
        }

        /* Style for the playlist item */
        .playlist-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        /* Hover effect for the playlist item */
        .playlist-item:hover {
            transform: translateY(-5px); /* Slight lift effect */
        }

        .playlist-name {
            margin-top: 10px;
            font-size: 14px;
            font-weight: bold;
            color: #333;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            width: 100px;
        }

        .playlist-detail-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .playlist-detail-container img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
        }

        .playlist-tracks {
            margin-top: 20px;
            text-align: left;
            max-width: 400px;
            width: 100%;
        }

        .track {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }
    </style>
</x-app-layout>
