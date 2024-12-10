<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotify Mood-Based Music Finder</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/style.css">
</head>
<body>
    <div class="container">
        <form>
            <input type="hidden" id="hidden_token">
            <div class="form-group row mt-4">
                <label for="select_mood" class="col-sm-2 col-form-label">Mood:</label>
                <div class="col-sm-10">
                    <select id="select_mood" class="form-control">
                        <option value="select">Select...</option>
                        <option value="happy">Happy</option>
                        <option value="sad">Sad</option>
                        <option value="energetic">Energetic</option>
                        <option value="calm">Calm</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <button type="submit" id="btn_submit" class="btn btn-success col-sm-12">Find Music</button>
            </div>
        </form>
        <div class="row">
            <div class="col-sm-6">
                <div class="list-group song-list"></div>
            </div>
            <div class="offset-md-1 col-sm-4" id="song-detail"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="/spotify.js"></script>
</body>
</html>
