$( document ).ready(function() {
    $('#upload_profile_image').on('click', function(e){
        e.preventDefault();
        $('#file_to_upload').trigger('click');
    });

    $('#file_to_upload').on('change', function(e){

        $.ajax({
            url:'addProfileImage',
            data:new FormData($("#profile_image_upload_form")[0]),
            async:false,
            type:'post',
            processData: false,
            contentType: false,
            success:function(response){
                $('#profile_image img').attr('src', response);
            },
            error:function(response){
                alert(response.responseJSON.file_to_upload[0]);
            },
        });
    });

    $('#upload_album_images').on('click', function(e){
        var selected_album_id = $('#select_album option:selected').val();
        if(!selected_album_id) {
            alert('album is not selected');
            e.preventDefault();
        }
    });

    $('#upload_album_images').on('change', function(){
        data = new FormData($("#album_images_form")[0]);
        $.ajax({
            url:'addImagesToAlbum',
            data:data,
            async:false,
            type:'post',
            processData: false,
            contentType: false,
            success:function(response){
                getAlbumImages();
            },
        });
    });

    $('#add_album_button').on('click', function(e){
        e.preventDefault();
        $.ajax({
            url:'addAlbum',
            data:new FormData($('#form_add_album')[0]),
            type:'post',
            processData: false,
            contentType: false,
            dataType:'json',
            success:function(response){
                $('#add_new_album_modal').modal('toggle');
                $('<option>').val(response.id).text(response.album_name).appendTo('#select_album');
            }
        });
    });

    $('#select_album').on('change', function(){
        getAlbumImages();
    });

    function getAlbumImages(){
        var selected_album_id = $('#select_album option:selected').val();
        $('#album_name_hidden').val(selected_album_id);
        if(selected_album_id) {
            data = {
                album_id: selected_album_id
            };
            $.ajax({
                url: 'getAlbumImages',
                data: data,
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    $(".image_container").remove();
                    for (i = 0; i < data.length; i++) {
                        var div = $('<div>', {
                            class: 'image_container'
                        }).append(
                            $('<img />', {
                                id: 'image_id' + data[i].id,
                                src: data[i].image_location,
                                alt: data[i].image_name,
                                width: '96px',
                                height: '100px'
                            })).append(
                            $('<button>',{
                                class: 'delete_image_button close',

                            }).text('X')
                        );
                        $('#album_images').append(div);
                    }
                    addEvents();
                }
            });
        }
    }

    $('#delete_selected_album').on('click', function(){
        var selected_album_id = $('#select_album option:selected').val();
        var token_value = $('input[name=_token]').val();
        if(selected_album_id) {
            data = {
                album_id: selected_album_id,
                _token:token_value
            };
            $.ajax({
                url: 'deleteAlbum',
                data: data,
                type: 'delete',
                success: function (data) {
                    $(".image_container").remove();
                }
            });
            $('#select_album option:selected').remove();
            $("#select_album option:eq(1)").attr('selected','selected');
            $('#album_name_hidden').val($("#select_album option:eq(1)").val());

        }else {
            alert('album is not selected');
        }
    });
function addEvents(){
    $('.delete_image_button').on('click', function(){
        alert('clicked s');
        var image_element_id = $(this).closest("div.image_container").find("img").attr("id");
        var selected_album_id = $('#select_album option:selected').val();
        var image_id = image_element_id.substr(8);
        var token_value = $('input[name=_token]').val();
        if(selected_album_id && image_id){
            data = {
                album_id: selected_album_id,
                id: image_id,
                _token:token_value
            };
            $.ajax({
                url: 'deleteAlbumImage',
                data: data,
                type: 'delete',
                success: function (data) {
                    getAlbumImages();
                }
            });

        }
    });
}
});

