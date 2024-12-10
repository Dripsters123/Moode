const APIController = (function() {
    const clientId = '0a5168c0671c4c92a545cdabceebb87c';
    const clientSecret = 'f5aea80eb2a94567913aab3f9b7759a5';

    // Private methods
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

    const _searchTracks = async (token, query) => {
        const result = await fetch(`https://api.spotify.com/v1/search?q=${query}&type=track&limit=10`, {
            method: 'GET',
            headers: { 'Authorization': 'Bearer ' + token }
        });

        const data = await result.json();
        return data.tracks.items;
    };

    const _getTrack = async (token, trackEndpoint) => {
        const result = await fetch(trackEndpoint, {
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
        searchTracks(token, query) {
            return _searchTracks(token, query);
        },
        getTrack(token, trackEndpoint) {
            return _getTrack(token, trackEndpoint);
        }
    };
})();

const UIController = (function() {
    const DOMElements = {
        selectMood: '#select_mood',
        buttonSubmit: '#btn_submit',
        divSonglist: '.song-list',
        divSongDetail: '#song-detail',
        hfToken: '#hidden_token'
    };

    return {
        inputField() {
            return {
                mood: document.querySelector(DOMElements.selectMood),
                submit: document.querySelector(DOMElements.buttonSubmit),
                tracks: document.querySelector(DOMElements.divSonglist),
                songDetail: document.querySelector(DOMElements.divSongDetail)
            };
        },

        createTrack(id, name) {
            const html = `<a href="#" class="list-group-item list-group-item-action" id="${id}">${name}</a>`;
            document.querySelector(DOMElements.divSonglist).insertAdjacentHTML('beforeend', html);
        },

        createTrackDetail(img, title, artist) {
            const detailDiv = document.querySelector(DOMElements.divSongDetail);
            detailDiv.innerHTML = `
                <div>
                    <img src="${img}" alt="Track Image">
                    <h5>${title}</h5>
                    <p>By: ${artist}</p>
                </div>
            `;
        },

        resetTrackDetail() {
            this.inputField().songDetail.innerHTML = '';
        },

        resetTracks() {
            this.inputField().tracks.innerHTML = '';
            this.resetTrackDetail();
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

    // Defining the query based on moods
    const moodQueries = {
        happy: 'danceability:0.7 energy:0.8 valence:0.6',
        sad: 'valence:0.2 acousticness:0.8',
        energetic: 'energy:0.9 danceability:0.8',
        calm: 'acousticness:0.8 instrumentalness:0.7'
    };

    const loadTracks = async (mood) => {
        const token = UICtrl.getStoredToken();
        const query = moodQueries[mood];
        const tracks = await APICtrl.searchTracks(token, query);
        UICtrl.resetTracks();
        tracks.forEach(track => UICtrl.createTrack(track.href, track.name));
    };

    DOMInputs.submit.addEventListener('click', async (e) => {
        e.preventDefault();
        const mood = DOMInputs.mood.value;
        if (mood !== 'select') {
            loadTracks(mood);
        }
    });

    DOMInputs.tracks.addEventListener('click', async (e) => {
        e.preventDefault();
        const token = UICtrl.getStoredToken();
        const trackEndpoint = e.target.id;
        const track = await APICtrl.getTrack(token, trackEndpoint);
        UICtrl.createTrackDetail(track.album.images[0].url, track.name, track.artists[0].name);
    });

    return {
        init() {
            console.log('App is starting');
            APICtrl.getToken().then(token => UICtrl.storeToken(token));
        }
    };
})(UIController, APIController);

APPController.init();
