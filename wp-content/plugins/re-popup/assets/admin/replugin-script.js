jQuery(document).ready(function($) {
    $('.create--popup').click(function(){
        $('.create--modal').toggleClass('show');
    });
    $('.create--modal').click(function(){
        $('.create--modal').toggleClass('show');
    });
    $(".create--modal div").click(function(e) {
        e.stopPropagation();
    });

    $('#create--popup').on('submit', function(e) {
        e.preventDefault();
        createNewPopup();
    })
    function createNewPopup() {
        var data = {
            'action': 'create_popup',
            'title': $('#create--popup input[name="title"]').val(),
            'image': $('#create--popup input[name="image"]').val(),
            'text': $('#create--popup textarea[name="text"]').val()
        };
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post(ajaxurl, data, function (response) {
            $('tbody').empty();
            $('tbody').append(response);

            $('.create--modal').toggleClass('show');
        });
    }
});