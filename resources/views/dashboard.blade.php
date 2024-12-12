<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Spotify Mood-Based Playlist Finder') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <!-- Mood Search Mode Selector -->
                <div class="flex space-x-4 mb-6">
                    <button id="btn-category-mode" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-colors">
                        Mood Categories
                    </button>
                    <button id="btn-camera-mode" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-colors">
                        Camera Mood Detection
                    </button>
                </div>

                <!-- Form for Mood Category Selection -->
                <form id="category-form" class="space-y-6">
                    <input type="hidden" id="hidden_token">
                    <div class="mb-4">
                        <label for="select_mood" class="block text-gray-700 text-lg font-medium">Mood:</label>
                        <select id="select_mood" class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="select">Select...</option>
                            <option value="happy">Happy</option>
                            <option value="sad">Sad</option>
                            <option value="energetic">Energetic</option>
                            <option value="romantic">Romantic</option>
                            <option value="angry">Angry</option>
                            <option value="workout">Workout</option>
                            <option value="gaming">Gaming</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" id="btn_submit" class="w-full bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 transition-colors">Find Playlists</button>
                    </div>
                </form>

                <!-- Camera Mood Detection -->
                <div id="camera-container" style="display:none;">
                    <video id="video" width="640" height="480" autoplay></video>
                    <button id="btn-capture" class="w-full bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 transition-colors mt-4">
                        Detect Mood via Camera
                    </button>
                </div>

                <!-- Playlist Section -->
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 playlist-list-container">
                    <div class="playlist-list flex space-x-4"></div>
                </div>

                <!-- Playlist Detail Section -->
                <div id="playlist-detail" class="mt-8">
                    <button id="back-to-list" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-colors mb-4" style="display: none;">
                        Back to Playlist List
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/face-api.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="spotify.js"></script>
    <script>
        // Switch between modes
        const btnCategoryMode = document.getElementById('btn-category-mode');
        const btnCameraMode = document.getElementById('btn-camera-mode');
        const categoryForm = document.getElementById('category-form');
        const cameraContainer = document.getElementById('camera-container');
        const selectMood = document.getElementById('select_mood');

        // Show category mood form and hide camera mode
        btnCategoryMode.addEventListener('click', () => {
            categoryForm.style.display = 'block';
            cameraContainer.style.display = 'none';
            btnCategoryMode.classList.add('bg-blue-600');
            btnCameraMode.classList.remove('bg-blue-600');
        });

        // Show camera mode and hide category form
        btnCameraMode.addEventListener('click', () => {
            categoryForm.style.display = 'none';
            cameraContainer.style.display = 'block';
            btnCategoryMode.classList.remove('bg-blue-600');
            btnCameraMode.classList.add('bg-blue-600');
        });

        // Handle form submission for mood selection (by categories)
        categoryForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const mood = selectMood.value;

            if (mood !== 'select') {
                findPlaylistsByMood(mood);
            } else {
                alert("Please select a mood.");
            }
        });

        // Camera Mood Detection
        const videoElement = document.getElementById('video');
        const captureButton = document.getElementById('btn-capture');
        let videoStream = null;

        // Start camera stream
        async function startCamera() {
            try {
                videoStream = await navigator.mediaDevices.getUserMedia({
                    video: true
                });
                videoElement.srcObject = videoStream;
            } catch (error) {
                console.error("Error accessing camera: ", error);
            }
        }

        // Stop camera stream
        function stopCamera() {
            if (videoStream) {
                let tracks = videoStream.getTracks();
                tracks.forEach(track => track.stop());
            }
        }

        // Detect mood based on face expression
        async function detectMoodFromCamera() {
            await faceapi.nets.ssdMobilenetv1.loadFromUri('/models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('/models');

            const detections = await faceapi.detectAllFaces(videoElement).withFaceLandmarks().withFaceDescriptors();
            
            if (detections.length > 0) {
                // Assuming you have a simple logic to determine mood from facial expressions, 
                // here we will trigger a mock mood. Replace with actual logic.
                const mood = 'happy'; // Replace with actual mood detection
                findPlaylistsByMood(mood);
            } else {
                alert('No face detected. Please try again.');
            }
        }

        // Detect mood on button click (camera mode)
        captureButton.addEventListener('click', () => {
            detectMoodFromCamera();
        });

        // Function to find playlists based on mood
        function findPlaylistsByMood(mood) {
            console.log("Searching playlists for mood: ", mood);

            // Call Spotify API or your server to fetch playlists based on mood
            // Placeholder: You can add the actual implementation to fetch playlists
        }

        // Start the camera when camera mode is selected
        btnCameraMode.addEventListener('click', startCamera);

        // Stop the camera when switching back to category mode
        btnCategoryMode.addEventListener('click', stopCamera);
    </script>
    <style>
    /* Add a max height and enable scrolling for the playlist list container */
    .playlist-list-container {
        max-height: 400px; /* You can adjust the height as needed */
        overflow-y: auto; /* Enables vertical scrolling when content overflows */
        scroll-behavior: smooth; /* Smooth scrolling when using the wheel */
    }

    /* Optional: Adding a subtle scrollbar appearance */
    .playlist-list-container::-webkit-scrollbar {
        width: 8px;
    }

    .playlist-list-container::-webkit-scrollbar-thumb {
        background-color: #48BB78;
        border-radius: 10px;
    }

    .playlist-list-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Style for the playlist image */
    .playlist-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #ccc;
        transition: transform 0.3s ease, border-color 0.3s ease;
    }

    /* Hover effect for playlist image */
    .playlist-item:hover .playlist-image {
        transform: scale(1.05); /* Slight zoom effect */
        border-color: #48BB78; /* Green border on hover */
    }

    /* Style for the playlist item */
    .playlist-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    /* Hover effect for the playlist item */
    .playlist-item:hover {
        transform: translateY(-5px); /* Slight lift effect */
    }

    .playlist-name {
        margin-top: 10px;
        font-size: 14px;
        font-weight: bold;
        color: #333;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        width: 100px;
    }

    .playlist-detail-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .playlist-detail-container img {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 50%;
    }

    .playlist-tracks {
        margin-top: 20px;
        text-align: left;
        max-width: 400px;
        width: 100%;
    }

    .track {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        border-bottom: 1px solid #ddd;
    }
</style>
</x-app-layout>
