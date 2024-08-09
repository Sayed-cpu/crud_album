<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Picture;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AlbumController extends Controller
{
    public function index()
    {
        $albums = Album::with('pictures')->get();
        return view('albums.index', compact('albums'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Validate each photo
        ]);
    
        // Create the album
        $album = Album::create(['name' => $request->name]);
    
        // Handle the file uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $album->addMedia($photo)
                      ->preservingOriginal() // Optional: to preserve the original file
                      ->toMediaCollection('photos'); // Save to 'photos' collection
            }
        }
    
        // return redirect()->back()->with('success', 'Album created successfully with photos.');
    }
    public function show($id){
        $album = Album::findOrFail($id);

        return view('albums.view',compact('album'));
    }
    public function edit($id){
        $album = Album::findOrFail($id);

        return view('albums.edit',compact('album'));
    }
    public function deleteAllPictures($id)
    {
        $album = Album::findOrFail($id);

        if ($album->media()->count() > 0) {
            // Delete all media associated with the album
            $album->clearMediaCollection('photos');
            $album->delete();
            
            return response()->json(['success' => true], 200);
        }

        return response()->json(['success' => false, 'message' => 'No media to delete'], 400);
    }

    public function movePictures($id)
    {
        $album = Album::findOrFail($id);
        $albums = Album::where('id', '!=', $id)->get(); // Exclude current album

        return view('albums.move', compact('album', 'albums'));
    }

    public function movePicturesToAnother(Request $request)
    {
        $sourceAlbumId = $request->input('source_album_id');
        $destinationAlbumId = $request->input('destination_album');
        
        $sourceAlbum = Album::findOrFail($sourceAlbumId);
        $destinationAlbum = Album::findOrFail($destinationAlbumId);

        // Move all media to the destination album
        foreach ($sourceAlbum->getMedia('photos') as $media) {
            $media->copy($destinationAlbum, 'photos');
        }

        // Clear media from the source album and delete it
        $sourceAlbum->clearMediaCollection('photos');
        $sourceAlbum->delete();

        return redirect()->route('albums.index')->with('success', 'Pictures moved successfully');
    }
    

    

    public function update(Request $request, Album $album)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'photos.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Optional: Validate photos if present
        ]);
    
        // Update album
        $album->update(['name' => $request->name]);
    
        // Handle the file uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $album->addMedia($photo)
                      ->preservingOriginal()
                      ->toMediaCollection('photos');
            }
        }
    
        return redirect()->route('albums.index');
    }
    public function deletePicture(Request $request, $id)
    {
        // Find the media item by its ID
        $media = Media::findOrFail($id);
    
        // Delete the media item
        $media->delete();
    
        return response()->json(['message' => 'Picture deleted successfully']);
    }
    


    
    public function destroy(Request $request, Album $album)
    {
        // Check if the album has any media
        if ($album->getMedia('photos')->count() > 0) {
            if ($request->action === 'delete') {
                // Delete all media associated with the album
                $album->clearMediaCollection('photos');
            } elseif ($request->action === 'move' && $request->move_to_album_id) {
                // Move media to another album
                $moveToAlbum = Album::find($request->move_to_album_id);
                
                if ($moveToAlbum) {
                    foreach ($album->getMedia('photos') as $media) {
                        // Move each media item to the new album
                        $media->copy($moveToAlbum, 'photos');
                    }
                    // Optionally, clear the media collection from the original album
                    $album->clearMediaCollection('photos');
                } else {
                    return redirect()->back()->with('error', 'Target album not found.');
                }
            }
        }
    
        // Delete the album
        $album->delete();
    
        return redirect()->back()->with('success', 'Album deleted successfully.');
    }
}    
