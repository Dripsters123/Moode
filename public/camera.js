document.addEventListener('DOMContentLoaded', () => {
    const video = document.getElementById('video');
    const canvas = document.getElementById('snapshot');
    const captureButton = document.getElementById('capture-btn');
    const moodResult = document.getElementById('mood-detection-result');
    const selectMood = document.getElementById('select_mood');

    // Access the camera
    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
            video.classList.remove('hidden');
        } catch (err) {
            console.error('Camera access denied or unavailable:', err);
            moodResult.textContent = 'Error accessing the camera.';
        }
    }

    // Capture an image from the video feed
    function captureImage() {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        return canvas.toDataURL('image/png'); // Base64 image data
    }

    // Mock mood detection function (replace with your actual API call)
    async function detectMood(imageData) {
        // Replace with real mood detection logic
        const mockMoods = ['happy', 'sad', 'energetic', 'calm', 'romantic', 'focus', 'angry', 'workout', 'gaming'];
        return mockMoods[Math.floor(Math.random() * mockMoods.length)]; // Random mood for demonstration
    }

    // Handle capture button click
    captureButton.addEventListener('click', async () => {
        const imageData = captureImage();
        moodResult.textContent = 'Detecting mood...';
        const detectedMood = await detectMood(imageData); // Replace with actual API call
        moodResult.textContent = `Detected mood: ${detectedMood}`;
        selectMood.value = detectedMood; // Auto-select detected mood in dropdown
    });

    // Initialize camera
    startCamera();
});
