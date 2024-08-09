<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Album;

class AlbumManager extends Component
{
    public $albums;
    public $showModal = false;
    public $selectedAlbum;

    public function mount()
    {
        $this->albums = Album::all();
    }

    public function create()
    {
        $this->selectedAlbum = new Album();
        $this->showModal = true;
    }

    public function edit(Album $album)
    {
        $this->selectedAlbum = $album;
        $this->showModal = true;
    }

    public function delete(Album $album)
    {
        // Check if the album has pictures
        if ($album->pictures->count()) {
            // Provide options to delete or move pictures
            // Implement logic for handling user choices
        } else {
            $album->delete();
            $this->albums = Album::all();
        }
    }

    public function render()
    {
        return view('livewire.album-manager');
    }
}
