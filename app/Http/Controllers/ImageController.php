<?php

namespace App\Http\Controllers;

use App\AlbumImage;
use App\UserAlbum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class ImageController extends Controller
{

    public function __construct()
    {
        $user = Auth::user();
    }

    public function addImagesToAlbum(Request $request){
        $files = $request->file('images');
        if($files) {
            $validator = $this->validator($request->all());

            if ($validator->fails()) {
                $this->throwValidationException(
                    $request, $validator
                );
            }
            $album_id = $request->album_name;
            $albumImagePath = 'images/albumPictures';
            if ($album_id && $files) {
                foreach ($files as $file) {
                    $fileName = $file->getClientOriginalName();
                    $imageName = time() . "-" . $fileName;
                    $file->move($albumImagePath, $imageName);
                    $image = new AlbumImage();
                    $image->image_name = pathinfo($fileName, PATHINFO_FILENAME);
                    $image->image_location = $imageName;
                    $image->album_id = $album_id;
                    $image->save();
                }
            }
        }
    }

    public function deleteAlbumImage(Request $request){
        $album_id = $request->album_id;
        $image_id = $request->id;
        if($album_id && $image_id){
            $image = AlbumImage::where('id', $image_id)
                ->where('album_id', $album_id)
                ->first();
            if($image) {
                unlink('images/albumPictures/'.$image->image_location);
                $deletedRows = AlbumImage::where('id', $image_id)
                    ->where('album_id', $album_id)
                    ->delete();
            }
        }
    }
    protected function validator(array $data)
    {
        $rules = $this->rules($data);
        return Validator::make($data, $rules);
    }

    public function rules(array $data)
    {
        $nbr = count($data['images']) - 1;
        foreach(range(0, $nbr) as $index) {
            $rules['images.' . $index] = 'image|max:4000';
        }
        return $rules;
    }
}
