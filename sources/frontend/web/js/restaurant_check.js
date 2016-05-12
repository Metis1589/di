var initRestaurantList = function() {

    $('.checked_block').on('click', function() {
        $(this).parents('.checked_block').css({
            'display': 'none'
        });
    });

    $('.select_set label').on('click', function(){
        var $_this = $(this);

        if ($_this.prev('input').prop('checked')) {
            $('<div class="checked_block"></div>')
                .insertAfter($_this.parents('.select_set'))
                .text($_this.text())
                .append('<i class="checked_block_carret"></i>');
        }
    });

    $('table.restaurants-list tbody tr').on('mouseover', function() {
        var $_this   = $(this)
          , trText   = $_this.attr('data-text')
          , trHref   = $_this.attr('data-href')
          , trOffset = $_this.offset().top
          , trHeight = $_this.height();

        $('.hover_text').show().css({
            top       : trOffset - 60,
            height    : trHeight,
            lineHeight: trHeight + 'px'
        }).text(trText).attr('href', trHref);

    });

    var $clickableItem = $('table.restaurants-list tbody tr');

    $('table.restaurants-list tbody tr').on('touchstart', function(e) {
        $('table.restaurants-list tbody tr').removeClass('touched');
    }).on('touchend', function(e) {
        $('table.restaurants-list tbody tr').removeClass('touched');
        $(this).addClass('touched');
        $clickableItem = $(this);
    }).on('click',function(e) {
        if ($(this).hasClass('touched')) {
            $(this).removeClass('touched');
        } else {
            window.location = $(this).attr('data-href');
        }
    });

    $(document)
        .bind('touchstart', function(e) {
            touchStartPos = $(window).scrollTop();
        })
        .bind('touchend', function(e) {
            var distance = touchStartPos - $(window).scrollTop();

            if (distance > 20 || distance < -20) {
            } else {
                if ($('table.restaurants-list tbody tr.touched').length) {
                    window.location = $clickableItem.attr('data-href');
                }
            }
        });

    $('.hover_text').on('mouseout', function() {
        $('.hover_text').hide();
    });

    $('.mask_modal').magnificPopup({
        mainClass   : 'mask_popup',
        showCloseBtn: false,
        callbacks   : {
            open: function() {
                $('.link_menu').addClass('open');
            },
            close: function() {
                $('.link_menu').removeClass('open');
            }
        }
    });
};

(function($, undefined) {
    angular.element(document).ready(function() {
        initRestaurantList();
    });
})(jQuery);