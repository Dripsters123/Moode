<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function showImageView()
    {
        // Fetch or generate the Spotify token (You can use your token retrieval method here)
        $spotifyToken = $this->getSpotifyToken(); // Example function to get the token

        // Pass the token to the view
        return view('image', compact('spotifyToken'));
    }

    private function getSpotifyToken()
    {
        // Fetch or generate your Spotify token here
        // For example, if using client credentials flow:
        $clientId = '0a5168c0671c4c92a545cdabceebb87c';
        $clientSecret = 'f5aea80eb2a94567913aab3f9b7759a5';

        $response = \Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'client_credentials',
        ], [
            'Authorization' => 'Basic ' . base64_encode("$clientId:$clientSecret"),
        ]);

        return $response->json()['access_token'] ?? null; // Return the token
    }
}

