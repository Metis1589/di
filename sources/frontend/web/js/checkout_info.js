;(function($, undefined){
    $(document).ready(function(){
        $('.slide_section').on('click', function(){
            $('.slide_section').removeClass('is_opened');
            $(this).addClass('is_opened');
        });
    });
})(jQuery);