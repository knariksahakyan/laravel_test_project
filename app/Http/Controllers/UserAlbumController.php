<?php

namespace App\Http\Controllers;

use App\AlbumImage;
use App\UserAlbum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserAlbumController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $user;

    public function __construct()
    {
        $user = Auth::user();
    }


    public function addAlbum(Request $request){
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        $user_id = Auth::user()->id;
        $album = new UserAlbum();
        $album->user_id = $user_id;
        $album->album_name = $request->album_name;
        $album->album_description = $request->album_description;
        $album->save();
        $album_arr = array('id' => $album->id, 'album_name' => $album->album_name);
        echo json_encode($album_arr);
    }

    public function getAlbumImages(Request $request){
        $album_id = $request->album_id;
        $albumImagePath = 'images/albumPictures/';
        $user_id = Auth::user()->id;
        $album = \DB::table('user_albums')
            ->select('id', 'album_name')
            ->where('user_id', '=', $user_id)
            ->where('id', '=', $album_id)
            ->first();
        if($album){
            $images = \DB::table('album_images')
                ->select('id', 'image_name', 'image_location')
                ->where('album_id', '=', $album->id)
                ->get();
            $image_arr = [];
            if($images) {
                foreach ($images as $image) {
                    $image_arr[] = array('id' => $image->id, 'image_name' => $image->image_name, 'image_location' => $albumImagePath.$image->image_location);
                }
            }
            echo json_encode($image_arr);
        }
    }
    /**
     * Get a validator for an incoming adding an user album.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'album_name' => 'required|max:255'
        ]);
    }

    public function deleteAlbum(Request $request){
        $user_id = Auth::user()->id;
        $album_id = $request->album_id;
        $images = AlbumImage::where('album_id', $album_id)->get();
        $images_location =[];
        if($images){
            foreach($images as $image){
                $images_location[] = $image->image_location;
                unlink('images/albumPictures/'.$image->image_location);
            }
        }
        $deletedRows = UserAlbum::where('user_id', $user_id)
            ->where('id', $album_id)
            ->delete();
    }
}
