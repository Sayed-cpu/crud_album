<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $album->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
            margin: 0;
            padding: 0;
        }
        .profile-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .profile-header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e6e6e6;
            padding-bottom: 20px;
        }
        .profile-header img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #e6e6e6;
        }
        .profile-header .profile-info {
            margin-left: 20px;
        }
        .profile-header .profile-info h1 {
            margin: 0;
            font-size: 24px;
        }
        .profile-header .profile-info p {
            color: #777;
        }
        .profile-actions {
            margin-top: 20px;
        }
        .profile-actions a,
        .profile-actions button {
            background: #0095f6;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 15px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            margin-right: 10px;
            transition: background 0.3s ease;
        }
        .profile-actions button {
            background: #e4405f;
        }
        .profile-actions a:hover,
        .profile-actions button:hover {
            background: #007bb5;
        }
        .profile-actions button:hover {
            background: #c13584;
        }
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
        }
        .gallery img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }
        #confirmationModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
        }
        .modal-content {
            background: #fff;
            color: #000;
            padding: 20px;
            border-radius: 8px;
            display: inline-block;
            position: relative;
            width: 80%;
            max-width: 500px;
        }
        .modal-content button {
            background: #0095f6;
            border: none;
            border-radius: 4px;
            padding: 10px 15px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            margin: 0 10px;
        }
        .modal-content button:hover {
            background: #007bb5;
        }
        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            @php
                $coverImage = $album->getFirstMediaUrl('photos');
            @endphp
            <img src="{{ $coverImage }}" alt="Album Cover">
            <div class="profile-info">
                <h1>{{ $album->name }}</h1>
                <p>{{ $album->description }}</p>
                <div class="profile-actions">
                    <a href="{{ route('albums.edit', $album->id) }}"><i class="fas fa-edit"></i> Edit</a>
                    <button class="deleteAlbumBtn" data-album-id="{{ $album->id }}"><i class="fas fa-trash"></i> Delete</button>
                </div>
            </div>
        </div>
        <div class="gallery">
            @foreach ($album->getMedia('photos') as $media)
                <img src="{{ $media->getUrl() }}" alt="{{ $media->name }}" class="gallery-image">
            @endforeach
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>This album is not empty. What would you like to do?</p>
            <button id="deleteAllPictures">Delete All Pictures</button>
            <button id="movePictures">Move Pictures to Another Album</button>
            <button id="cancelAction" style="margin: 10px">Cancel</button>
        </div>
    </div>
    
    <script>
        document.querySelectorAll('.deleteAlbumBtn').forEach(button => {
            button.addEventListener('click', function() {
                const albumId = this.getAttribute('data-album-id');
                const modal = document.getElementById('confirmationModal');
    
                modal.style.display = 'block';
    
                document.getElementById('deleteAllPictures').onclick = function() {
                    fetch(`/albums/${albumId}/delete-all`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }).then(response => {
                        if (response.ok) {
                            window.location.reload();
                        }
                    });
                };
    
                document.getElementById('movePictures').onclick = function() {
                    window.location.href = `/albums/${albumId}/move-pictures`;
                };
    
                document.getElementById('cancelAction').onclick = function() {
                    modal.style.display = 'none'; // Close the modal
                };
            });
        });
    
        // Close the modal when clicking on the close button or outside the modal
        document.querySelector('.close').addEventListener('click', function() {
            document.getElementById('confirmationModal').style.display = 'none';
        });
    
        window.onclick = function(event) {
            if (event.target == document.getElementById('confirmationModal')) {
                document.getElementById('confirmationModal').style.display = 'none';
            }
        };
    </script>
</body>
</html>
