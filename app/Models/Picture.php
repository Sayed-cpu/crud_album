<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Picture extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['album_id', 'name', 'file'];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
