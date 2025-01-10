<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload & Playlist Search</title>
    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js"></script>
</head>
<body>

    <div>
        <label for="image-upload">Upload an Image</label>
        <input type="file" id="image-upload" accept="image/*">
    </div>

    <!-- Hidden token for Spotify API -->
    

    <!-- Button to fetch playlists based on detected emotion -->
    <button id="btn-submit">Get Playlists</button>

    <!-- Playlist Display -->
    <div class="playlist-list-container" style="display:none;">
        <div id="playlist-list"></div>
    </div>

    <!-- Canvas for Face API detection -->
    <canvas id="canvas"></canvas>

    <script src="image.js"></script>
</body>
</html>
