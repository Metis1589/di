;(function($, undefined){
    $(document).ready(function(){
        var defaults = {
            onlyOne: false,
            slideSpeed: 500
        };
        $.fn.dineinSelect = function(params){
            var options = $.extend({}, defaults, params);

            var $this = this;
            var pseudoInput =  $this.find('.pseudo_input');
            var thisUl = $this.find('ul');
            var startPageY, currentPageY, finalPageY, delta;
            var intID;
            var discret,discretScroll, discretTouch, event;


            $.each($this, function() {
                    $(this).addClass('slset');
                    $(this).find('.pseudo_input:not(.no-update)').text($(this).find('input').attr('placeholder'));
                    if ($(this).find('.select_set_carret').length == 0) {
                        $('<i class="select_set_carret"></i>').insertBefore($(this).find('.pseudo_input'));
                    }
            });
            thisUl.slideUp(0);
            $this.children('.description').hide();
            $this.find('.select_set').slideUp(0);
            $this.find('.selected_options').slideUp(0);

            $('.checkout_page, .restaurant_page').off('click').on('click', function(e) {
                if($(e.target).parents('.user-settings-mobile > div, .add-user-mobile > div').length > 0){
                    return;
                }else{
                    $('.slset').removeClass('opened');
                    $('.slset ul').slideUp(options.slideSpeed);
                }
            });

            /*----------touch detect-----------*/
            if("ontouchstart" in window){
                /*discret = pseudoInput.height();*/
                event = 'click';
            } else {
                discret = 1;
                event = 'click';
            }

            /*-----------open/close-----------*/
            $this.find('.select_set_carret, .pseudo_input:not(.select-no-handler)').unbind('click');
            $this.find('.select_set_carret, .pseudo_input:not(.select-no-handler)').bind('click', function(e) {
                e.stopPropagation();
                var $_this = $(this);
                $_this.trigger('dineinSelect.click');
                var childSelects =  $_this.parent('.select_set').children('menu-option').children('div').children('.select_set');
                if($_this.parent('.slset').hasClass('opened')){
                    $_this.parent('.slset').removeClass('opened');
                    $_this.siblings('ul').slideUp(options.slideSpeed);
                    childSelects.slideUp(options.slideSpeed);
                    childSelects.parent().children('.selected_options').slideUp(options.slideSpeed);
                    $_this.parent('.select_set').children('.description').hide();

                } else {
                    if(options.onlyOne === true){
                        $('.slset').removeClass('opened');
                        $('.slset ul').slideUp(options.slideSpeed);
                    }
                    $_this.parent('.slset').addClass('opened');
                    $_this.siblings('ul').slideDown(options.slideSpeed);
                    childSelects.slideDown(options.slideSpeed);
                    childSelects.parent().children('.selected_options').slideDown(options.slideSpeed);
                    //$_this.parent('.select_set').parent().children('.selected_options').slideDown(options.slideSpeed);
                    $_this.parent('.select_set').children('.description').show();

                }
            });

            /*--------------change item-------------------*/
            $(document).on('click', '.select_filter:not(.multiple) li, .delivery_items li, .sidebar_select li, .exptype_select li', function() {
                var $_this = $(this);
                var title, value;
                value = $_this.attr('data-value') ? $_this.attr('data-value') : $_this.text();
                title = $_this.attr('data-title') ? $_this.attr('data-title') : $_this.text();
                $_this.parents('.slset').find('ul').slideUp(options.slideSpeed);
                $_this.parents('ul').siblings('.pseudo_input:not(.static)').text(title);
                $_this.parent('.slset').find('input').attr('value', value);
                if (!$_this.parents('.slset').hasClass('abs-select')) {
                    $_this.parent('.slset').removeClass('opened').addClass('selected');
                }
            });

            /*width checkboxes*/
            //$this.find('label').on('click', function(e){
            //    e.preventDefault();
            //    $(this).prev('input').prop('checked', function(i, val){
            //        return !val;
            //    });
            //    e.stopPropagation();
            //});

            return this;
        };
    });
})(jQuery);