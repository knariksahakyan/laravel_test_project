<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAlbum extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','album_name','album_description'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_albums';

    public function images(){
        return $this->hasMany('App\AlbumImage', 'album_id', 'id');
    }
}
