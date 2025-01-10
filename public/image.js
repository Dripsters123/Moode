window.onload = async function () {
    // Wait for face-api.js to be loaded
    await faceapi.nets.ssdMobilenetv1.loadFromUri('/models');
    await faceapi.nets.faceExpressionNet.loadFromUri('/models');
    console.log("Face-api.js models loaded");

    // UI Elements
    const DOMInputs = {
        mood: document.querySelector("#mood-input"),
        submit: document.querySelector("#btn-submit"),
        playlists: document.querySelector("#playlist-list"),
        playlistListContainer: document.querySelector(".playlist-list-container"),
        readButton: document.querySelector("#read-button"),
        imageUpload: document.querySelector("#image-upload"),
    };

    // Predefined mood queries for Spotify search
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

    // API Controller to interact with Spotify
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
                `https://api.spotify.com/v1/search?q=${query}&type=playlist&limit=7`,
                {
                    method: "GET",
                    headers: { Authorization: "Bearer " + token },
                }
            );

            const data = await result.json();
            return data.playlists.items;
        };

        return {
            getToken() {
                return _getToken();
            },
            searchPlaylists(token, query) {
                return _searchPlaylists(token, query);
            },
        };
    })();

    // UI Controller for updating the DOM
    const UIController = (function () {
        return {
            createPlaylist(id, name, imageUrl) {
                const html = `
                    <a href="#" class="playlist-item" id="${id}">
                        <img src="${imageUrl || 'placeholder.jpg'}" alt="Playlist Cover" class="playlist-image">
                        <span class="playlist-name">${name}</span>
                    </a>`;
                DOMInputs.playlists.insertAdjacentHTML("beforeend", html);
            },

            showPlaylists() {
                DOMInputs.playlistListContainer.style.display = "grid";
            },

            resetPlaylists() {
                DOMInputs.playlists.innerHTML = "";
            }
        };
    })();

    // App Controller for handling logic
    const APPController = (function (UICtrl, APICtrl) {
        const loadPlaylists = async (mood) => {
            const token = await APICtrl.getToken();
            const query = moodQueries[mood];
            const playlists = await APICtrl.searchPlaylists(token, query);

            UICtrl.resetPlaylists();

            if (playlists && playlists.length > 0) {
                playlists.forEach((playlist) => {
                    // Safely handle missing images
                    const imageUrl = (playlist.images && Array.isArray(playlist.images) && playlist.images.length > 0) 
                        ? playlist.images[0].url 
                        : "placeholder.jpg";
                    UICtrl.createPlaylist(playlist.id, playlist.name, imageUrl);
                });
                UICtrl.showPlaylists();
            } else {
                DOMInputs.playlists.innerHTML = `
                    <p class="text-muted">No playlists found for this mood. Please try another mood.</p>
                `;
            }
        };

        DOMInputs.submit.addEventListener("click", async (e) => {
            e.preventDefault();
            const mood = DOMInputs.mood.value;
            if (mood && mood !== "select") {
                await loadPlaylists(mood);
            }
        });

        return {
            init() {
                console.log("App is starting");
            },
            loadPlaylists,  // Expose loadPlaylists function to the outside scope
        };
    })(UIController, APIController);

    APPController.init();

    // Face API Emotion Detection & Playlist Search
    async function detectEmotionAndSearchPlaylist(imageElement) {
        // Detect emotions
        const detections = await faceapi.detectAllFaces(imageElement).withFaceExpressions();

        // If emotions detected, pick the dominant emotion
        if (detections && detections.length > 0) {
            const emotions = detections[0].expressions;
            const dominantEmotion = Object.keys(emotions).reduce((a, b) => emotions[a] > emotions[b] ? a : b);

            // Map emotion to a query
            const moodQuery = moodQueries[dominantEmotion];
            if (moodQuery) {
                // Display detected mood in the input field
                DOMInputs.mood.value = dominantEmotion;

                // Search playlists based on the detected mood
                await APPController.loadPlaylists(dominantEmotion);  // Call loadPlaylists from APPController
            } else {
                console.error("No matching mood for detected emotion.");
            }
        } else {
            console.error("No faces detected.");
        }
    }

    // Image Upload Event Handler
    DOMInputs.readButton.addEventListener('click', async () => {
        const file = DOMInputs.imageUpload.files[0];
        if (file) {
            const imageElement = document.createElement('img');
            imageElement.src = URL.createObjectURL(file);

            // Once the image is loaded, run the emotion detection
            imageElement.onload = () => {
                detectEmotionAndSearchPlaylist(imageElement);
            };
        }
    });
};
