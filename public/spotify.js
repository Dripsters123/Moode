const APIController = (function() {
    const clientId = '0a5168c0671c4c92a545cdabceebb87c';
    const clientSecret = 'f5aea80eb2a94567913aab3f9b7759a5';

    const _getToken = async () => {
        const result = await fetch('https://accounts.spotify.com/api/token', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Authorization': 'Basic ' + btoa(clientId + ':' + clientSecret)
            },
            body: 'grant_type=client_credentials'
        });

        const data = await result.json();
        return data.access_token;
    };

    const _searchPlaylists = async (token, query) => {
        const result = await fetch(`https://api.spotify.com/v1/search?q=${query}&type=playlist&limit=20`, {
            method: 'GET',
            headers: { 'Authorization': 'Bearer ' + token }
        });

        const data = await result.json();
        return data.playlists.items;
    };

    const _getPlaylistDetails = async (token, playlistId) => {
        const result = await fetch(`https://api.spotify.com/v1/playlists/${playlistId}`, {
            method: 'GET',
            headers: { 'Authorization': 'Bearer ' + token }
        });

        const data = await result.json();
        return data;
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
        }
    };
})();

const UIController = (function() {
    const DOMElements = {
        selectMood: '#select_mood',
        buttonSubmit: '#btn_submit',
        divPlaylistList: '.playlist-list',
        divPlaylistDetail: '#playlist-detail',
        hfToken: '#hidden_token'
    };

    return {
        inputField() {
            return {
                mood: document.querySelector(DOMElements.selectMood),
                submit: document.querySelector(DOMElements.buttonSubmit),
                playlists: document.querySelector(DOMElements.divPlaylistList),
                playlistDetail: document.querySelector(DOMElements.divPlaylistDetail)
            };
        },

        createPlaylist(id, name, imageUrl) {
            const html = `
                <a href="#" class="list-group-item list-group-item-action" id="${id}">
                    <img src="${imageUrl || 'placeholder.jpg'}" alt="Playlist Cover" class="img-thumbnail mr-2" style="width:50px; height:50px;">
                    ${name}
                </a>`;
            document.querySelector(DOMElements.divPlaylistList).insertAdjacentHTML('beforeend', html);
        },

        createPlaylistDetail(imageUrl, name, description) {
            const detailDiv = document.querySelector(DOMElements.divPlaylistDetail);
            detailDiv.innerHTML = `
                <div>
                    <img src="${imageUrl || 'placeholder.jpg'}" alt="Playlist Image" style="width:100%;">
                    <h5>${name}</h5>
                    <p>${description || 'No description available.'}</p>
                </div>
            `;
        },

        resetPlaylistDetail() {
            this.inputField().playlistDetail.innerHTML = '';
        },

        resetPlaylists() {
            this.inputField().playlists.innerHTML = '';
            this.resetPlaylistDetail();
        },

        storeToken(value) {
            document.querySelector(DOMElements.hfToken).value = value;
        },

        getStoredToken() {
            return document.querySelector(DOMElements.hfToken).value;
        }
    };
})();

const APPController = (function(UICtrl, APICtrl) {
    const DOMInputs = UICtrl.inputField();

    // Expanded mood options
    const moodQueries = {
        happy: 'happy',
        sad: 'sad',
        energetic: 'energetic upbeat',
        calm: 'calm relaxation',
        romantic: 'romantic love',
        focus: 'focus concentration',
        party: 'party dance',
        workout: 'workout exercise'
    };

    const loadPlaylists = async (mood) => {
        const token = UICtrl.getStoredToken();
        const query = moodQueries[mood];
        const playlists = await APICtrl.searchPlaylists(token, query);

        UICtrl.resetPlaylists();

        if (playlists && playlists.length > 0) {
            playlists.forEach(playlist => {
                if (playlist && playlist.id && playlist.name) {
                    const imageUrl = playlist.images?.[0]?.url || 'placeholder.jpg';
                    UICtrl.createPlaylist(playlist.id, playlist.name, imageUrl);
                }
            });
        } else {
            UICtrl.inputField().playlists.innerHTML = `
                <p class="text-muted">No playlists found for this mood. Please try another mood.</p>
            `;
        }
    };

    DOMInputs.submit.addEventListener('click', async (e) => {
        e.preventDefault();
        const mood = DOMInputs.mood.value;
        if (mood !== 'select') {
            await loadPlaylists(mood);
        }
    });

    DOMInputs.playlists.addEventListener('click', async (e) => {
        e.preventDefault();
        const token = UICtrl.getStoredToken();
        const playlistId = e.target.closest('a')?.id;

        if (playlistId) {
            const playlist = await APICtrl.getPlaylistDetails(token, playlistId);

            if (playlist) {
                UICtrl.createPlaylistDetail(
                    playlist.images?.[0]?.url || 'placeholder.jpg',
                    playlist.name,
                    playlist.description || 'No description available.'
                );
            }
        }
    });

    return {
        init() {
            console.log('App is starting');
            APICtrl.getToken().then(token => UICtrl.storeToken(token));
        }
    };
})(UIController, APIController);

APPController.init();
