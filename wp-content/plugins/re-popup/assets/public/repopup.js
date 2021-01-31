jQuery(function($){
    var active = 1;
    var currentMousePos = { x: 1, y: 1 };
    $(document).mouseleave(function(){

        if( active == 1){

            active = 0;
        }
    });
    $(window).blur(function() {
        if(active == 1){

            active = 0;
        }
    });
})