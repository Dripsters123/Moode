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
    return data.playlists.items;
  };

  const _getPlaylistDetails = async (token, playlistId) => {
    const result = await fetch(
      `https://api.spotify.com/v1/playlists/${playlistId}`,
      {
        method: "GET",
        headers: { Authorization: "Bearer " + token },
      }
    );

    const data = await result.json();
    return data;
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
    getPlaylistDetails(token, playlistId) {
      return _getPlaylistDetails(token, playlistId);
    },
    getPlaylistTracks(token, playlistId) {
      return _getPlaylistTracks(token, playlistId);
    },
  };
})();

const UIController = (function () {
  const DOMElements = {
    selectMood: "#select_mood",
    buttonSubmit: "#btn_submit",
    divPlaylistList: ".playlist-list",
    divPlaylistDetail: "#playlist-detail",
    hfToken: "#hidden_token",
    playlistListContainer: ".playlist-list-container",
  };

  return {
    inputField() {
      return {
        mood: document.querySelector(DOMElements.selectMood),
        submit: document.querySelector(DOMElements.buttonSubmit),
        playlists: document.querySelector(DOMElements.divPlaylistList),
        playlistDetail: document.querySelector(DOMElements.divPlaylistDetail),
        playlistListContainer: document.querySelector(
          DOMElements.playlistListContainer
        ),
      };
    },

    createPlaylist(id, name, imageUrl) {
      const html = `
                <a href="#" class="playlist-item" id="${id}">
                    <img src="${
                      imageUrl || "placeholder.jpg"
                    }" alt="Playlist Cover" class="playlist-image">
                    <span class="playlist-name">${name}</span>
                </a>`;
      document
        .querySelector(DOMElements.divPlaylistList)
        .insertAdjacentHTML("beforeend", html);
    },

    createPlaylistDetail(imageUrl, name, description) {
      const detailDiv = document.querySelector(DOMElements.divPlaylistDetail);
      detailDiv.innerHTML = `
                <div class="playlist-detail-container">
                    <img src="${
                      imageUrl || "placeholder.jpg"
                    }" alt="Playlist Image">
                    <h3>${name}</h3>
                    <p>${description || "No description available."}</p>
                </div>
            `;
    },

    createPlaylistTracks(tracks) {
      let trackListHTML = '<div class="playlist-tracks">';
      tracks.forEach((track) => {
        trackListHTML += `
                    <div class="track">
                        <span>${track.track.name}</span>
                        <span>${track.track.artists[0].name}</span>
                    </div>
                `;
      });
      trackListHTML += "</div>";
      document
        .querySelector(DOMElements.divPlaylistDetail)
        .insertAdjacentHTML("beforeend", trackListHTML);
    },

    resetPlaylistDetail() {
      this.inputField().playlistDetail.innerHTML = "";
    },

    resetPlaylists() {
      this.inputField().playlists.innerHTML = "";
      this.resetPlaylistDetail();
    },

    storeToken(value) {
      document.querySelector(DOMElements.hfToken).value = value;
    },

    getStoredToken() {
      return document.querySelector(DOMElements.hfToken).value;
    },

    hidePlaylists() {
      this.inputField().playlistListContainer.style.display = "none";
    },

    showPlaylists() {
      this.inputField().playlistListContainer.style.display = "grid";
    },

    showHistoryConfirmation() {
      alert("Playlist saved to history!");
    },
  };
})();

const APPController = (function (UICtrl, APICtrl) {
  const DOMInputs = UICtrl.inputField();

  const moodQueries = {
    happy: "happy",
    sad: "sad",
    energetic: "energetic upbeat",
    calm: "calm relaxation",
    romantic: "romantic love",
    focus: "focus concentration",
    angry: "angry",
    workout: "workout exercise",
    gaming: "gaming",
  };

  const loadPlaylists = async (mood) => {
    const token = UICtrl.getStoredToken();
    const query = moodQueries[mood];
    const playlists = await APICtrl.searchPlaylists(token, query);

    UICtrl.resetPlaylists();

    if (playlists && playlists.length > 0) {
      playlists.forEach((playlist) => {
        if (playlist && playlist.id && playlist.name) {
          const imageUrl = playlist.images?.[0]?.url || "placeholder.jpg";
          UICtrl.createPlaylist(playlist.id, playlist.name, imageUrl);
        }
      });

      // Add click event to each playlist
      document.querySelectorAll(".playlist-item").forEach((item) => {
        item.addEventListener("click", async (e) => {
          e.preventDefault();
          const playlistId = e.target.closest(".playlist-item").id;
          const tracks = await APICtrl.getPlaylistTracks(token, playlistId);
          UICtrl.showPlaylists();

          // Save the playlist to history
          await saveToHistory(
            playlistId,
            item.querySelector(".playlist-name").textContent,
            item.querySelector("img").src
          );
        });
      });
    } else {
      UICtrl.inputField().playlists.innerHTML = `
                <p class="text-muted">No playlists found for this mood. Please try another mood.</p>
            `;
    }
  };

  const saveToHistory = async (playlistId, playlistName, playlistImage) => {
    const token = document.getElementById("hidden_token").value;
    const response = await fetch("/save-to-history", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
          .content,
      },
      body: JSON.stringify({
        playlist_id: playlistId,
        playlist_name: playlistName,
        playlist_image: playlistImage,
      }),
    });

    if (response.ok) {
      UICtrl.showHistoryConfirmation();
    } else {
      console.error("Error saving playlist to history");
    }
  };

  DOMInputs.submit.addEventListener("click", async (e) => {
    e.preventDefault();
    const mood = DOMInputs.mood.value;
    if (mood !== "select") {
      await loadPlaylists(mood);
    }
  });

  DOMInputs.playlists.addEventListener("click", async (e) => {
    e.preventDefault();
    const token = UICtrl.getStoredToken();
    const playlistId = e.target.closest("a")?.id;

    if (playlistId) {
      const playlist = await APICtrl.getPlaylistDetails(token, playlistId);
      const tracks = await APICtrl.getPlaylistTracks(token, playlistId);

      if (playlist) {
        UICtrl.hidePlaylists();
        UICtrl.createPlaylistDetail(
          playlist.images?.[0]?.url || "placeholder.jpg",
          playlist.name,
          playlist.description || "No description available."
        );
        UICtrl.createPlaylistTracks(tracks);
      }
    }
  });

  return {
    init() {
      console.log("App is starting");
      APICtrl.getToken().then((token) => UICtrl.storeToken(token));
    },
  };
})(UIController, APIController);

APPController.init();
