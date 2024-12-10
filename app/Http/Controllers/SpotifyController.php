<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SpotifyController extends Controller
{
    /**
     * Get recommendations based on mood.
     */
    public function getRecommendations(Request $request)
    {
        $mood = $request->input('mood');
        $accessToken = $this->getSpotifyAccessToken();

        // Map mood to Spotify genres
        $seedGenres = $this->mapMoodToGenre($mood);

        // Make Spotify API call for recommendations
        $response = Http::withToken($accessToken)->get('https://api.spotify.com/v1/recommendations', [
            'seed_genres' => $seedGenres,
            'limit' => 10,
        ]);

        // Process API response
        $tracks = collect($response->json()['tracks'])->map(function ($track) {
            return [
                'id' => $track['id'],
                'name' => $track['name'],
                'artist' => $track['artists'][0]['name'],
                'url' => $track['external_urls']['spotify'],
            ];
        });

        return response()->json(['tracks' => $tracks]);
    }

    /**
     * Get Spotify access token using client credentials flow.
     */
    private function getSpotifyAccessToken()
    {
        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'client_credentials',
            'client_id' => env('SPOTIFY_CLIENT_ID'),
            'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
        ]);

        return $response->json()['access_token'];
    }

    /**
     * Map moods to Spotify seed genres.
     */
    private function mapMoodToGenre($mood)
    {
        $moodGenres = [
            'happy' => 'pop',
            'sad' => 'acoustic',
            'energetic' => 'rock',
            'calm' => 'chill',
            'romantic' => 'romance',
        ];

        return $moodGenres[$mood] ?? 'pop'; // Default to "pop"
    }
}
