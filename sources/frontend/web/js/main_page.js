;(function($,undefined){
    function logoShow() {
        if ($(window).scrollTop() >= $(window).height()) {
            $('.header_logo_set').show();
        } else {
            $('.header_logo_set').hide();
        }
    }
    $('.header_logo_set').on('click',function(){
        location.href = $(this).attr('href');
    });
    if(location.pathname == '/'){
        $(document).ready(function(){
            logoShow();
            $(window).scroll(logoShow);
        });
    }
})(jQuery);