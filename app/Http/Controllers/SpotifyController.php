<?php

// app/Http/Controllers/SpotifyController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use Illuminate\Support\Facades\Auth;

class SpotifyController extends Controller
{
    // Save playlist to history
    public function saveToHistory(Request $request)
    {
        $validated = $request->validate([
            'playlist_id' => 'required|string',
            'playlist_name' => 'required|string',
            'playlist_image' => 'nullable|string',
        ]);

        $history = new History();
        $history->user_id = Auth::id(); // Store the current logged-in user's ID
        $history->playlist_id = $validated['playlist_id'];
        $history->playlist_name = $validated['playlist_name'];
        $history->playlist_image = $validated['playlist_image'] ?? 'placeholder.jpg'; // Fallback if no image is provided
        $history->save();

        return response()->json(['message' => 'Playlist saved to history']);
    }

    // Get the history of the logged-in user
    public function getHistory()
    {
        $history = History::where('user_id', Auth::id())->get();
        return view('history', compact('history'));
    }
}
