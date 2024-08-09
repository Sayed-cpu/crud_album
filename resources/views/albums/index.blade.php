<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Album Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.css">
    <link rel="stylesheet" href="https://unpkg.com/filepond/dist/filepond.css">
    <style>
        /* Basic styling for the page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .album {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            position: relative;
            background-color: #fafafa;
        }

        .album h2 {
            margin-top: 0;
        }

        .album .pictures {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .album .pictures .photo-container {
            position: relative;
        }

        .album .pictures img {
            max-width: 100px;
            border-radius: 5px;
        }

        .delete-photo-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 0, 0, 0.7);
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            text-align: center;
            line-height: 20px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        .album-actions {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .album-actions button {
            margin-left: 5px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 10px;
        }

        .modal h2 {
            margin-top: 0;
        }

        .modal label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        .modal input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .modal button {
            padding: 10px 15px;
            border: none;
            background-color: #28a745;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal button:hover {
            background-color: #218838;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .button-container {
            display: flex;
            justify-content: center;
            margin-bottom: 5px
        }
        #createAlbumBtn {
            background-color: #0095f6;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 15px 25px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            display: flex;
            align-items: center;
        }
        #createAlbumBtn:hover {
            background-color: #007bb5;
        }
        #createAlbumBtn:active {
            transform: scale(0.98);
        }
        #createAlbumBtn i {
            margin-right: 8px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Album Management</h1>
        <div class="button-container">
            <button id="createAlbumBtn"><i class="fas fa-plus"></i> Create Album</button>
        </div>        <!-- List of Albums -->
        @foreach ($albums as $album)
            <div class="album" data-id="{{ $album->id }}">
                <h2>{{ $album->name }}</h2>
                <div class="album-actions">
                    <a href="{{ route('albums.show', $album->id) }}" class="">View</a>
                    {{-- <button class="deleteAlbumBtn">Delete</button> --}}
                </div>
                <div class="pictures">
                    @foreach ($album->getMedia('photos') as $media)
                        <div class="photo-container">
                            <img src="{{ $media->getUrl() }}" alt="{{ $media->name }}" height="100px" width="100px">
                            <button class="delete-photo-btn" data-media-id="{{ $media->id }}">X</button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Modals and other UI elements -->
        <!-- Create Album Modal -->
        <div id="createAlbumModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Create Album</h2>
                <form id="createAlbumForm">
                    @csrf
                    <label for="albumName">Album Name:</label>
                    <input type="text" id="albumName" name="name" required>
                    <label for="albumPhotos">Add Photos:</label>
                    <input type="file" name="photos[]" id="albumPhotos" multiple>
                    <button type="submit">Create</button>
                </form>
            </div>
        </div>

        <!-- Edit Album Modal -->
        <div id="editAlbumModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Edit Album</h2>
                <form id="editAlbumForm">
                    @csrf
                    <input type="hidden" id="editAlbumId" name="id">
                    <label for="editAlbumName">Album Name:</label>
                    <input type="text" id="editAlbumName" name="name" required>
                    <label for="editAlbumPhotos">Add More Photos:</label>
                    <input type="file" name="photos[]" id="editAlbumPhotos" multiple>
                    <img id="editAlbumPhoto" src="" alt="Current Album Photo" style="display: none; width: 100px; height: auto;">
                    <button type="submit">Save</button>
                </form>
            </div>
        </div>

        <!-- Delete Album Modal -->
        <div id="deleteAlbumModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Delete Album</h2>
                <p>What do you want to do with the pictures?</p>
                <button id="deletePicturesBtn">Delete All Pictures</button>
                <button id="movePicturesBtn">Move Pictures to Another Album</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Show create album modal
            $('#createAlbumBtn').click(function() {
                $('#createAlbumModal').show();
            });

            // Populate and show edit album modal
            $('.editAlbumBtn').click(function() {
                const $album = $(this).closest('.album');
                const albumId = $album.data('id');
                const albumName = $album.find('h2').text();
                const photoUrl = $album.data('photo-url');
        
                $('#editAlbumId').val(albumId);
                $('#editAlbumName').val(albumName);
        
                // Show the current photo if available
                if (photoUrl) {
                    $('#editAlbumPhoto').attr('src', photoUrl).show();
                } else {
                    $('#editAlbumPhoto').hide(); // Hide the image if no URL is provided
                }
        
                $('#editAlbumModal').show();
            });

            // Hide modal on close button click
            $('.close').click(function() {
                $(this).closest('.modal').hide();
            });

            // Handle create album form submission
            $('#createAlbumForm').submit(function(e) {
                e.preventDefault();
                const url = '{{ route('albums.store') }}';
                let formData = new FormData(this);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    contentType: false
                    ,
                    processData: false,
                    success: function(response) {
                        alert('Album created successfully!');
                        $('#createAlbumModal').hide();
                        location.reload();
                    },
                    error: function(xhr) {
                        displayError('Error creating album', xhr);
                    }
                });
            });

            // Handle edit album form submission
            $('#editAlbumForm').submit(function(e) {
                e.preventDefault();
                const albumId = $('#editAlbumId').val();
                const url = `/albums/${albumId}`; // Adjust the URL dynamically
            
                let formData = new FormData(this);
            
                $.ajax({
                    url: url,
                    method: 'PUT',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert('Album updated successfully!');
                        $('#editAlbumModal').hide();
                        location.reload();
                    },
                    error: function(xhr) {
                        displayError('Error updating album', xhr);
                    }
                });
            });

            // Handle photo deletion
            $(document).on('click', '.delete-photo-btn', function() {
                const mediaId = $(this).data('media-id');
                const $photoContainer = $(this).closest('.photo-container');
                
                if (confirm('Are you sure you want to delete this photo?')) {
                    $.ajax({
                        url: `/photos/${mediaId}`,
                        method: 'DELETE',
                        success: function(response) {
                            alert('Photo deleted successfully!');
                            $photoContainer.remove();
                        },
                        error: function(xhr) {
                            displayError('Error deleting photo', xhr);
                        }
                    });
                }
            });

            // Handle album deletion choices
            $('#deletePicturesBtn').click(function() {
                const albumId = $('.album').data('id'); // Get album ID from context
                $.ajax({
                    url: `/albums/${albumId}/delete-pictures`,
                    method: 'POST',
                    data: { action: 'delete_all' },
                    success: function(response) {
                        alert('All pictures deleted successfully!');
                        $('#deleteAlbumModal').hide();
                        location.reload();
                    },
                    error: function(xhr) {
                        displayError('Error deleting pictures', xhr);
                    }
                });
            });

            $('#movePicturesBtn').click(function() {
                const albumId = $('.album').data('id'); // Get album ID from context
                const newAlbumId = prompt('Enter the ID of the album to move pictures to:');
                if (newAlbumId) {
                    $.ajax({
                        url: `/albums/${albumId}/move-pictures`,
                        method: 'POST',
                        data: { new_album_id: newAlbumId },
                        success: function(response) {
                            alert('Pictures moved successfully!');
                            $('#deleteAlbumModal').hide();
                            location.reload();
                        },
                        error: function(xhr) {
                            displayError('Error moving pictures', xhr);
                        }
                    });
                }
            });

            // Close modals when clicking outside the modal content
            $(window).click(function(event) {
                if ($(event.target).hasClass('modal')) {
                    $(event.target).hide();
                }
            });

            // Utility function for displaying error messages
            function displayError(message, xhr) {
                let errorMessage = `${message}: `;
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMessage += response.message;
                    }
                    if (response.errors) {
                        errorMessage += '\n' + JSON.stringify(response.errors);
                    }
                } catch (e) {
                    errorMessage += xhr.responseText;
                }
                alert(errorMessage);
            }
        });
    </script>
</body>
</html>
