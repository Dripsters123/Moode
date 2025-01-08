<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotify & Emotion Detection</title>
    <style>
        #container {
            display: flex;
            justify-content: center;
            position: relative;
        }
        canvas {
            position: absolute;
        }
        #video-feed {
            border: 1px solid #ccc;
        }
        .playlist-list-container {
            display: none;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .playlist-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .playlist-image {
            width: 50px;
            height: 50px;
            margin-right: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div id="app">
        <h1>Emotion-Based Playlist Finder</h1>
        <div id="container">
            <video id="video-feed" height="560" width="720" autoplay></video>
            <canvas id="canvas"></canvas>
        </div>
        <div class="playlist-list-container">
            <div id="playlist-list"></div>
        </div>
        <button id="btn-submit">Get Playlists</button>
        <input type="hidden" id="hidden_token">
    </div>
    <script src="face-api.min.js"></script>
    <script src="scripts.js"></script>
</body>
</html>
