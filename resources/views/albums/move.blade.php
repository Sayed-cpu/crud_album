<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Move Pictures</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            margin: 0 0 20px;
            font-size: 24px;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-group button {
            background-color: #0095f6;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .form-group button:hover {
            background-color: #007bb5;
        }
        .back-button {
            background-color: #e4405f;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        .back-button:hover {
            background-color: #c13584;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('albums.show', $album->id) }}" class="back-button"><i class="fas fa-arrow-left"></i> Back</a>
        <h1>Move Pictures from "{{ $album->name }}"</h1>
        <form method="POST" action="{{ route('albums.movePicturesToAnother') }}">
            @csrf
            <input type="hidden" name="source_album_id" value="{{ $album->id }}">
            
            <div class="form-group">
                <label for="destination_album">Select Destination Album:</label>
                <select name="destination_album" id="destination_album" required>
                    @foreach ($albums as $destinationAlbum)
                        <option value="{{ $destinationAlbum->id }}">{{ $destinationAlbum->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit">Move Pictures</button>
            </div>
        </form>
    </div>
</body>
</html>
