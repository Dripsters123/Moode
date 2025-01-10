<x-app-layout>

    <style>
        .playlist-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding-bottom: 20px;
            padding-top: 10px;
            justify-content: center;
        }

        .playlist-card {
            width: 200px;
            flex-shrink: 0;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }

        .playlist-card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .playlist-card img {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .card-body {
            padding: 10px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .card-text {
            font-size: 14px;
            color: #555;
        }

        /* Media Query for Smaller Screens */
        @media (max-width: 768px) {
            .playlist-card {
                width: 100%;
                max-width: 300px;
            }
        }

        /* Media Query for Large Screens */
        @media (min-width: 1024px) {
            .playlist-card {
                width: 300px;
            }
        }

        /* Hidden playlist songs */
        .playlist-songs {
            display: none;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 8px;
            margin-top: 10px;
        }

        .playlist-songs ul {
            padding-left: 20px;
        }

        .playlist-songs li {
            font-size: 14px;
            color: #555;
        }
    </style>

    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Your Playlist History</h2>

        @if ($history->isEmpty())
            <p class="text-gray-600">No playlists found in your history.</p>
        @else
            <div class="playlist-container">
                @foreach ($history as $entry)
                    <div class="playlist-card" onclick="showSongs('{{ $entry->playlist_id }}', '{{ $entry->created_at->timestamp }}')">
                        <img src="{{ $entry->playlist_image }}" class="card-img-top" alt="Playlist Image" style="height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $entry->playlist_name }}</h5>
                            <p class="card-text">Playlist ID: {{ $entry->playlist_id }}</p>
                        </div>
                    </div>

                    <!-- Playlist Top 5 Songs -->
                    <div id="songs-{{ $entry->playlist_id }}-{{ $entry->created_at->timestamp }}" class="playlist-songs">
                        <h6 class="text-lg font-bold">Top 5 Songs:</h6>
                        <ul>
                            @if(is_array($entry->songs) || is_object($entry->songs))
                                @foreach($entry->songs as $song)
                                    <li>{{ $song }}</li>
                                @endforeach
                            @else
                                <li>No songs available.</li>
                            @endif
                        </ul>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        // Function to toggle the songs visibility when a playlist card is clicked
        function showSongs(playlistId, createdAtTimestamp) {
            // Unique songs ID based on playlistId and timestamp
            var songsId = 'songs-' + playlistId + '-' + createdAtTimestamp;
            
            var songs = document.getElementById(songsId);

            // If songs are currently hidden, show them, else hide them
            if (songs.style.display === "none" || songs.style.display === "") {
                songs.style.display = "block"; // Show songs
            } else {
                songs.style.display = "none"; // Hide songs
            }
        }
    </script>
</x-app-layout>
