<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('user/activation/{token}', 'Auth\RegisterController@activateUser')->name('user.activate');

//User Controller
Route::get('/home', 'UserController@index');
Route::post('addProfileImage', 'UserController@addProfileImage');
Route::get('getUserImage', 'UserController@getUserImage');

//User Album Controller
Route::post('addAlbum', 'UserAlbumController@addAlbum');
Route::delete('deleteAlbum', 'UserAlbumController@deleteAlbum');
Route::get('getAlbumImages', 'UserAlbumController@getAlbumImages');

//Album Image Controller
Route::delete('deleteAlbumImage', 'ImageController@deleteAlbumImage');
Route::post('addImagesToAlbum', 'ImageController@addImagesToAlbum');

Route::get('profilePictures/{filename}', function ($filename)
{

    $path = resource_path() . '/images/profilePictures/' . $filename;

    if(!File::exists($path)) abort(404);

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
