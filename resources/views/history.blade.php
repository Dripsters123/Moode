<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Playlist History') }}
        </h2>
    </x-slot>

    <style>
        .playlist-container {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 20px;
            padding-bottom: 20px;
        }
        .playlist-card {
            width: 200px;
            flex-shrink: 0;
        }
        @media (max-width: 768px) {
            .playlist-container {
                flex-wrap: wrap;
                justify-content: center;
            }
            .playlist-card {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>

    <div class="container">
        <h2>Your Playlist History</h2>

        @if ($history->isEmpty())
            <p>No playlists found in your history.</p>
        @else
            <div class="playlist-container">
                @foreach ($history as $entry)
                    <div class="card playlist-card">
                        <img src="{{ $entry->playlist_image }}" class="card-img-top" alt="Playlist Image" style="height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $entry->playlist_name }}</h5>
                            <p class="card-text">Playlist ID: {{ $entry->playlist_id }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
