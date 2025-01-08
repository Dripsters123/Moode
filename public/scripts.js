let detectedEmotion = ""; // Declare `detectedEmotion` at the top level to share across different parts

// Camera and face detection setup
const run = async () => {
  try {
    // Start the camera stream
    const stream = await navigator.mediaDevices.getUserMedia({
      video: true,
      audio: false,
    });

    const videoFeedEl = document.getElementById("video-feed");
    videoFeedEl.srcObject = stream;

    // Load pre-trained face-api models
    await Promise.all([
      faceapi.nets.ssdMobilenetv1.loadFromUri("./models"),
      faceapi.nets.faceLandmark68Net.loadFromUri("./models"),
      faceapi.nets.faceRecognitionNet.loadFromUri("./models"),
      faceapi.nets.ageGenderNet.loadFromUri("./models"),
      faceapi.nets.faceExpressionNet.loadFromUri("./models"),
    ]);

    // Make the canvas the same size as the video
    const canvas = document.getElementById("canvas");
    canvas.style.left = videoFeedEl.offsetLeft;
    canvas.style.top = videoFeedEl.offsetTop;
    canvas.height = videoFeedEl.height;
    canvas.width = videoFeedEl.width;

    // Set up facial recognition and emotion detection
    setInterval(async () => {
      const faceData = await faceapi
        .detectAllFaces(videoFeedEl)
        .withFaceLandmarks()
        .withFaceExpressions();

      canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);

      // Resize and draw detections
      const resizedData = faceapi.resizeResults(faceData, videoFeedEl);
      faceapi.draw.drawDetections(canvas, resizedData);
      faceapi.draw.drawFaceLandmarks(canvas, resizedData);
      faceapi.draw.drawFaceExpressions(canvas, resizedData);

      // Get the dominant emotion
      faceData.forEach((face) => {
        const expressions = face.expressions;
        detectedEmotion = Object.keys(expressions).reduce((a, b) =>
          expressions[a] > expressions[b] ? a : b
        );
      });
    }, 200);
  } catch (error) {
    console.error("Error starting the camera", error);
  }
};

// Spotify API Controller
const APIController = (function () {
  const clientId = "0a5168c0671c4c92a545cdabceebb87c";
  const clientSecret = "f5aea80eb2a94567913aab3f9b7759a5";

  const _getToken = async () => {
    const result = await fetch("https://accounts.spotify.com/api/token", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
        Authorization: "Basic " + btoa(clientId + ":" + clientSecret),
      },
      body: "grant_type=client_credentials",
    });
    const data = await result.json();
    return data.access_token;
  };

  const _searchPlaylists = async (token, query) => {
    const result = await fetch(
      `https://api.spotify.com/v1/search?q=${query}&type=playlist&limit=20`,
      {
        method: "GET",
        headers: { Authorization: "Bearer " + token },
      }
    );
    const data = await result.json();
    if (data && data.playlists && data.playlists.items) {
      return data.playlists.items;
    } else {
      console.error("Error fetching playlists:", data);
      return [];
    }
  };

  const _getPlaylistTracks = async (token, playlistId) => {
    const result = await fetch(
      `https://api.spotify.com/v1/playlists/${playlistId}/tracks?limit=5`,
      {
        method: "GET",
        headers: { Authorization: "Bearer " + token },
      }
    );
    const data = await result.json();
    return data.items;
  };

  return {
    getToken() {
      return _getToken();
    },
    searchPlaylists(token, query) {
      return _searchPlaylists(token, query);
    },
    getPlaylistTracks(token, playlistId) {
      return _getPlaylistTracks(token, playlistId);
    },
  };
})();

// UI Controller
const UIController = (function () {
  return {
    inputField() {
      return {
        submit: document.querySelector("#btn-submit"),
        playlistList: document.querySelector("#playlist-list"),
      };
    },

    createPlaylist(id, name, imageUrl) {
      const html = `
        <div class="playlist-item" id="${id}">
            <img src="${
              imageUrl || "placeholder.jpg"
            }" alt="Playlist Cover" class="playlist-image">
            <span class="playlist-name">${name}</span>
        </div>
      `;
      document
        .querySelector("#playlist-list")
        .insertAdjacentHTML("beforeend", html);
    },

    showPlaylists() {
      document.querySelector(".playlist-list-container").style.display = "grid";
    },

    resetPlaylists() {
      document.querySelector("#playlist-list").innerHTML = "";
    },

    showPlaylistTracks(tracks) {
      const trackListHTML = tracks
        .map(
          (track) => `
            <div class="track-item">
                <span class="track-name">${track.track.name}</span>
                <span class="track-artist">${track.track.artists[0].name}</span>
            </div>
          `
        )
        .join("");

      document.querySelector(".playlist-list-container").innerHTML = `
        <h2>Top 5 Songs</h2>
        <div class="track-list">${trackListHTML}</div>
      `;
    },

    // New method for displaying confirmation when a playlist is saved to history
    showHistoryConfirmation() {
      alert("Playlist added to history!");
    },
  };
})();

// App Controller
const APPController = (function (UICtrl, APICtrl) {
  const DOMInputs = UICtrl.inputField();

  const moodQueries = {
    happy: "happy",
    sad: "sad",
    angry: "angry",
    neutral: "neutral",
    surprised: "surprised",
  };

  const loadPlaylists = async (mood) => {
    const token = document.getElementById("hidden_token").value;
    const query = moodQueries[mood];
    const playlists = await APICtrl.searchPlaylists(token, query);

    UICtrl.resetPlaylists();

    if (playlists && playlists.length > 0) {
      playlists.forEach((playlist) => {
        if (playlist && playlist.id && playlist.name) {
          const imageUrl =
            playlist.images && playlist.images.length > 0
              ? playlist.images[0].url
              : "placeholder.jpg";

          UICtrl.createPlaylist(playlist.id, playlist.name, imageUrl);
        } else {
          console.error("Missing required properties in playlist:", playlist);
        }
      });

      // Add click event to each playlist
      document.querySelectorAll(".playlist-item").forEach((item) => {
        item.addEventListener("click", async (e) => {
          const playlistId = e.target.closest(".playlist-item").id;
          const tracks = await APICtrl.getPlaylistTracks(token, playlistId);
          UICtrl.showPlaylistTracks(tracks);

          // Save the playlist to history (new feature)
          await saveToHistory(
            playlistId,
            item.querySelector(".playlist-name").textContent,
            item.querySelector("img").src
          );
        });
      });
    } else {
      UICtrl.inputField().playlistList.innerHTML = `
                <p class="text-muted">No playlists found for this mood. Please try another mood.</p>
            `;
    }
  };

  // Function to save playlist to the history
  const saveToHistory = async (playlistId, playlistName, playlistImage) => {
    const token = document.getElementById("hidden_token").value;
    const response = await fetch("/save-to-history", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
          .content, // Get the CSRF token from the meta tag
      },
      body: JSON.stringify({
        playlist_id: playlistId,
        playlist_name: playlistName,
        playlist_image: playlistImage,
      }),
    });

    if (response.ok) {
      UICtrl.showHistoryConfirmation(); // Show confirmation message
    } else {
      console.error("Error saving playlist to history");
    }
  };

  // Listen for the 'Get Playlists' button click
  DOMInputs.submit.addEventListener("click", async () => {
    const mood = detectedEmotion || "neutral";
    await loadPlaylists(mood);
    UICtrl.showPlaylists();
  });

  return {
    init() {
      console.log("App is starting");
      APICtrl.getToken().then((token) => {
        document.getElementById("hidden_token").value = token;
      });
    },
  };
})(UIController, APIController);

// Initialize the app and start the camera
APPController.init();
run(); // Run the camera and face detection
