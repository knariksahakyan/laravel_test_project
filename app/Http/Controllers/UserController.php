<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $user;

    public function __construct()
    {
        $this->middleware('auth');
        $this->user = Auth::user();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userAlbums = $this->getUserAlbums();
        return view('home', compact('userAlbums'));
    }

    public function getUserAlbums(){
        return Auth::user()->albums;
    }
    public function addProfileImage(Request $request){
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        $fileName = $this->uploadProfileImage($request);
        $user = Auth::user();
        $user->activation_link  = $fileName;
        $user->update();
        $pofileImagePath = "images/profilePictures";
        echo $pofileImagePath.'/'.$fileName;
    }
    public function uploadProfileImage(Request $request){
        $pofileImagePath = "images/profilePictures";
        $file = $request->file('file_to_upload');
        $imageName = time()."-".$file->getClientOriginalName();
        if(Auth::user()->activation_link){
            unlink($pofileImagePath.'/'.Auth::user()->activation_link);
        }
        $file->move($pofileImagePath, $imageName);
        return $imageName;
    }

    public function getUserImage(){

        echo $this->user->activation_link;

    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'file_to_upload' => 'required|image'
        ]);
    }
}
