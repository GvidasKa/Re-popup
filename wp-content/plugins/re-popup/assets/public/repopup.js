jQuery(function($){
    var active = 1;
    var currentMousePos = { x: 1, y: 1 };
    $(document).mouseleave(function(){

        if( active == 1){
            $('.exit--popup--wrapp').css('display','flex');
            $('.exit--popup--wrapp').css('opacity',1);
            active = 0;
        }
    });
    $(window).blur(function() {
        if(active == 1){
            $('.exit--popup--wrapp').css('display','flex');
            $('.exit--popup--wrapp').css('opacity',1);
            active = 0;
        }
    });
    $('.close--popup').on('click',function(){
        $('.exit--popup--wrapp').css('display','none');
        $('.exit--popup--wrapp').css('opacity',0);
    })
})