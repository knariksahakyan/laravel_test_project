@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Home
                    {{ Auth::user()->first_name }}
                    {{ Auth::user()->last_name }}
                </div>

                <div class="panel-body">

                    <div id="profile_image" class="col-md-5">
                        <form id="profile_image_upload_form" class="form-horizontal" role="form" method="POST" action="{{ url('/uploadProfileImage') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @if(Auth::user()->activation_link):
                            <img src="/images/profilePictures/{{ Auth::user()->activation_link }}"  alt="Smiley face" style="width: 138px;height: 124px;">
                            @else
                            <img src="/images/profilePictures/default.png"  alt="Smiley face" style="width: 138px;height: 124px;">
                            @endif
                            <input type="file" name="file_to_upload" id="file_to_upload" style="display: none">
                            <div class="form-group">
                                <div>
                                    <button id="upload_profile_image" type="submit" class="btn btn-primary" name="submit">
                                        Change Profile Image
                                    </button>
                                </div>
                            </div>
                            @if ($errors->has('file_to_upload'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('file_to_upload') }}</strong>
                                    </span>
                            @endif

                        </form>
                    </div>
                    <div id="user_album_info">
                        <div class="form-group">
                            <button id="add_new_album_button" type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_new_album_modal">
                                    Add New Album
                                </button>
                        </div>
                        <div class="form-group col-md-3 pull-right">
                            <label for="select_album">Select an Album</label>
                        <select class="selectpicker" class="form-control" id="select_album">
                            <option value="" disabled selected>Select Album</option>
                            @if($userAlbums)
                            @foreach ($userAlbums as $userAlbum)
                                <option value="{{$userAlbum->id}}">{{ $userAlbum->album_name }}</option>
                            @endforeach
                            @endif
                        </select>
                        </div>
                        <div class="form-group">
                            <div id="">{{ csrf_field() }}
                            <input class="btn btn-primary col-md-3" type="button" id="delete_selected_album" value="Delete Seleted Album">
                        </div>
                        <div id="add_new_album_modal" class="col-md-5 col-md-offset-4 modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- add album modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Add New Album</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" role="form" id="form_add_album" method="post" action="{{ url('/uploadProfileImage') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('album_name') ? ' has-error' : '' }}">
                            <label for="album_name" class="col-md-4 control-label">Album Name</label>

                            <div class="col-md-6">
                                <input id="album_name" type="text" class="form-control" name="album_name" value="{{ old('album_name') }}" required autofocus>

                                @if ($errors->has('album_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('album_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('album_description') ? ' has-error' : '' }}">
                            <label for="album_description" class="col-md-4 control-label">Album Description</label>

                            <div class="col-md-6">
                                <textarea id="album_description" type="text" class="form-control" name="album_description" value="{{ old('album_description') }}" autofocus></textarea>
                                @if ($errors->has('album_description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('album_description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-4">
                                <button id="add_album_button" type="submit" class="btn btn-primary" name="submit">
                                    Add New Album
                                </button>
                            </div>
                        </div>
                    </form>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="upload_button_container col-md-3">
                            <form id="album_images_form" class="form-horizontal" role="form" enctype='multipart/form-data'>
                                {{ csrf_field() }}
                                <label class="btn btn-primary">
                                    Upload Images for Selected Album
                                    <input id="upload_album_images"  multiple type="file" name='images[]' style="display: none;">
                                </label>
                                <input id="album_name_hidden" type="hidden" name="album_name" style="display: none">
                            </form>
                        </div>
                        <div id="album_images" class="col-md-7" height="500px">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="{{ URL::asset('js/home.js') }}"></script>
