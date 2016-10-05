<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlbumImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'album_id','image_name','image_location'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'album_images';

    public function album(){
        $this->belongsTo('App\UserAlbum', 'id', 'album_id');
    }
}
