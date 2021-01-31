jQuery(document).ready(function($) {

    // Popups functions like show, hide
    $('.create--popup').click(function(){
        $('.create--modal').toggleClass('show');
    });

    $('.repop--modal').click(function(){
        $(this).toggleClass('show');
    });

    $(".repop--modal div").click(function(e) {
        e.stopPropagation();
    });


    // Popup image functions
    $('.repop--modal input[name="image"]').change(function() {
        readURL(this);
    });

    $(".popup--image .remove--image").on('click',function() {
        $(this).parent().css('border','4px dashed #b4b9be');
        $(this).parent().find('input').val('');
        $(this).parent().find('.upload--preview').attr('src','/repopup/wp-content/plugins/re-popup/assets/images/upload-icon.png');
        $(this).parent().find('p').css('display','block');
        $(this).css('display','none');
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('.upload--preview').attr('src', e.target.result);
                $(input).parent().find('p').css('display','none');
                $(input).parent().css('border','none');
                $(input).parent().find('.remove--image').css('display','block');
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }



    // CRUD Functions and function callers
    var DeleteIds = [];
    $('.popups--table .delete').each(function(){
        $(document).on('change',this, function(){
            DeleteIds = [];
            $('.popups--table .delete').each(function(){
                if($(this).prop("checked") == true){
                    DeleteIds.push($(this).attr('tableid'));
                }
            });
            if(DeleteIds.length != 0){
                $('.delete--popup').prop("disabled", false);
            } else{
                $('.delete--popup').prop("disabled", true);

            }
        });
    });
    $('.delete--popup').on('click', function(){
        deletePopups(DeleteIds);
    });

    $(document).on('click','table button' ,function(){
        editPopup($(this).attr('tableid'));
    });

    $('#create--popup').on('submit', function(e) {
        e.preventDefault();
        createNewPopup();
    });

    $('#edit--popup').on('submit', function(e) {
        e.preventDefault();
        updatePopup();
    });

    function createNewPopup() {

        var fd = new FormData();
        var title = $('#create--popup input[name="title"]').val();
        var files = $('#create--popup input[name="image"]')[0].files;
        var text = $('#create--popup textarea[name="text"]').val();

        fd.append('title',title);
        fd.append('file',files[0]);
        fd.append('text',text);
        fd.append('action','create_popup');

        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                $('#create--popup .upload--preview').attr('src', '/repopup/wp-content/plugins/re-popup/assets/images/upload-icon.png');
                $('#create--popup .popup--image p').css('display','block');
                $('tbody').empty();
                $('tbody').append(response);
                $('#create--popup input[name="title"]').val('');
                $('#create--popup input[name="image"]').val('');
                $('#create--popup textarea[name="text"]').val('')
                $('.create--modal').toggleClass('show');
            },
        });

    }

    function editPopup(id) {
        var data = {
            'action': 'edit_popup',
            'id': id
        };
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post(ajaxurl, data, function (response) {
            console.log(JSON.parse(response))
            response= JSON.parse(response);
            $('#edit--popup input[name="id"]').val(response['ID']);
            $('#edit--popup input[name="title"]').val(response['title']);
            if(response['image'] != '') {
                $('#edit--popup .upload--preview').attr('src', response['image']);
                // $('#edit--popup input[name="image"]').val(response['image']);
                $('#edit--popup .popup--image p').css('display','none');
                $('#edit--popup .popup--image').css('border','none');
                $('#edit--popup .popup--image .remove--image').css('display','block');
            }
            $('#edit--popup textarea[name="text"]').val(response['text'])
            $('.edit--modal').toggleClass('show');
        });
    }
    function updatePopup() {
        var data = {
            'action': 'update_popup',
            'id': $('#edit--popup input[name="id"]').val(),
            'title': $('#edit--popup input[name="title"]').val(),
            'image': $('#edit--popup input[name="image"]').val(),
            'text': $('#edit--popup textarea[name="text"]').val()
        };
        jQuery.post(ajaxurl, data, function (response) {
            $('tbody').empty();
            $('tbody').append(response);
            $('#edit--popup input[name="id"]').val('');
            $('#edit--popup input[name="title"]').val('');
            $('#edit--popup input[name="image"]').val('');
            $('#edit--popup textarea[name="text"]').val('')
            $('.edit--modal').toggleClass('show');
        });
    }
    function deletePopups(ids) {
        var data = {
            'action': 'delete_popups',
            'ids': ids
        };
        jQuery.post(ajaxurl, data, function (response) {
            $('tbody').empty();
            $('tbody').append(response);
            $('.delete--popup').prop("disabled", true);
        });
    }
});