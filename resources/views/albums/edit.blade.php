<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #0095f6;
            font-size: 16px;
        }
        .back-button i {
            margin-right: 5px;
        }
        h1 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group button {
            background-color: #0095f6;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .form-group button:hover {
            background-color: #007bb5;
        }
        .photo-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .photo-gallery img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('albums.index') }}" class="back-button"><i class="fas fa-arrow-left"></i> Back</a>
        <h1>Edit Album</h1>

        <form action="{{ route('albums.update', $album->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Album Name:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $album->name) }}" required>
            </div>

            <div class="form-group">
                <label for="cover_picture">Cover Picture:</label>
                <input type="file" id="cover_picture" name="photos[]" multiple>
                @if ($album->getMedia('photos')->isNotEmpty())
                    <div class="photo-gallery">
                        @foreach ($album->getMedia('photos') as $img)
                            <img src="{{ $img->getUrl() }}" alt="{{ $img->name }}">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="form-group">
                <button type="submit">Update Album</button>
            </div>
        </form>
    </div>
</body>
</html>
