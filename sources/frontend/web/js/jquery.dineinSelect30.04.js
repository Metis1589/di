;(function($, undefined){
    $(document).ready(function(){
        var defaults = {
            timeInterval: 15,
            frameHeight: 170,
            minDrag: 10,
            onlyOne: false
        };
        $.fn.dineinSelect = function(params){
            var options = $.extend({}, defaults, params);
            
            var $this = this;
            var pseudoInput =  $this.find('.pseudo_input');
            var thisUl = $this.find('ul');
            var startPageY, currentPageY, finalPageY, delta;
            var intID;
            var discret,discretScroll, discretTouch, event;
            
            $.each($this, function(){
                $(this).addClass('select_set');
                $(this).find('.pseudo_input').text($(this).find('input').attr('placeholder'));
                $('<i class="select_set_carret"></i>').insertBefore($(this).find('.pseudo_input'));
                $('<div class="carrer_top"></div>').insertBefore($(this).find('ul')).addClass('hidden_carret');
                $('<div class="carrer_bottom"></div>').insertAfter($(this).find('.carrer_top'));
            });
            thisUl.css({bottom: 0});
            
            /*----------touch detect-----------*/
            if("ontouchstart" in window){
                /*discret = pseudoInput.height();*/
                event = 'click';
                $('.touch_test').text('touch');
            } else {
                discret = 1;
                event = 'mouseenter';
                $('.touch_test').text('no touch');
            } 
            
            /*-----------open/close-----------*/
            $this.find('.select_set_carret, .pseudo_input').on('click', function(){
                var $_this = $(this);
                if($_this.parents('.select_set').hasClass('opened')){
                    $_this.parents('.select_set').removeClass('opened').animate({
                        height: pseudoInput.height()
                    });
                } else {
                    if(options.onlyOne === true){
                        $('.select_set').removeClass('opened').animate({
                            height: pseudoInput.height()
                        }, 200);
                    }
                    $_this.parents('.select_set').addClass('opened').animate({
                        height: options.frameHeight + pseudoInput.height()
                    }, function(){
                        $_this.parents('.select_set').find('.carrer_bottom').addClass('ready');
                    });
                    thisUl.css({bottom: 0});
                }
            });
            
            /*--------------hover----------------*/
            $('.carrer_bottom').on(event, function(e){
                var $_this = $(this);
                if($_this.hasClass('ready')){
                    if(event == 'mouseenter'){
                        intID = setInterval(function(){
                            $_this.siblings('ul').css('bottom', function(i, b){
                                b = parseInt(b) + discret;
                                /*console.log(b);*/
                                if((b < ($_this.siblings('ul').outerHeight() - options.frameHeight + 2*pseudoInput.height()))&& b>0 && !$_this.hasClass('hidden_carret') ){
                                    $_this.parents('.select_set').find('.carrer_top').removeClass('hidden_carret');
                                    return b;
                                } 
//                                if(b == ($_this.siblings('ul').outerHeight() - options.frameHeight + 2*pseudoInput.height())){
//                                    $_this.addClass('hidden_carret');
//                                }
                            });
                        }, options.timeInterval);
                    }
                    else {
                        discret = pseudoInput.height();
                        var b = parseInt($_this.siblings('ul').css('bottom')) + discret;
                        if(b < ($_this.siblings('ul').outerHeight() - options.frameHeight + 2*pseudoInput.height())){
                            $_this.parents('.select_set').find('.carrer_top').removeClass('hidden_carret');
                            $_this.siblings('ul').animate({
                                'bottom': b
                            },100);
                            console.log('if'+b);
                        } else {
                            $_this.addClass('hidden_carret');
                            $_this.siblings('ul').animate({
                                'bottom': ($_this.siblings('ul').outerHeight() - options.frameHeight + 2*pseudoInput.height())
                            },100);
                            console.log('else'+b);
                        }
                    }
                }
            }).on('mouseleave', function(){
                clearInterval(intID);
            });
            $('.carrer_top').on(event, function(){
                var $_this = $(this);
                if(event == 'mouseenter'){
                    $_this.parents('.select_set').find('.carrer_bottom').removeClass('hidden_carret');
                    intID = setInterval(function(){
                        $_this.siblings('ul').css('bottom', function(i, b){
                            b = parseInt(b) - discret;
                            if(b > 0){
                                return b;
                            } else {
                                $_this.addClass('hidden_carret');
                                return 0;
                            }
                        });
                    }, options.timeInterval);
                } else {
                    discret = pseudoInput.height();
                    var b = parseInt($_this.siblings('ul').css('bottom')) - discret;
                    if(b > 0){
                        $_this.parents('.select_set').find('.carrer_bottom').removeClass('hidden_carret');
                        $_this.siblings('ul').animate({
                            'bottom': b
                        });
                    } else {
                        $_this.siblings('ul').animate({
                            'bottom': 0
                        });
                        $_this.addClass('hidden_carret');
                    }
                }
            }).on('mouseleave', function(){
                clearInterval(intID);
            });
            
            /*--------------*/
            /*$('.hidden_carret').on('mouseenter click', function(e){
                e.preventDefault();
            });*/
            
            /*--------------change item-------------------*/
            thisUl.find('li').on('click', function(){
                var $_this = $(this);
                var title, value;
                value = $_this.attr('data-value') ? $_this.attr('data-value') : $_this.text();
                title = $_this.attr('data-title') ? $_this.attr('data-title') : $_this.text();
                $_this.parents('ul').siblings('.pseudo_input').text(title);
                $_this.parents('.select_set').find('input').attr('value', value);
                $_this.parents('.select_set').animate({
                    height: pseudoInput.height()
                }).removeClass('opened').addClass('selected');
            });
            
            /*-------------touch---------------------*/
            $this.find('ul').on('touchstart', function(event){
                event.preventDefault();
                startPageY = event.originalEvent.touches[0].pageY;
                currentPageY = 0;
            });
            $this.find('ul').on('touchmove', function(event){
                currentPageY = event.originalEvent.touches[0].pageY;
            });
            $this.find('ul li').on('touchend', function(event){
                var $_this = $(this).parent();
                finalPageY = startPageY - currentPageY;
                delta = finalPageY - startPageY;
                if( (delta < defaults.minDrag && delta > -defaults.minDrag) ){
                    $(this).click();
                    startPageY = currentPageY = finalPageY = 0;
                } else {
                    discretTouch = pseudoInput.height()*3;
                    var t = parseInt($_this.css('bottom'));
                    if( finalPageY > defaults.minDrag ){
                        if((t + discretTouch) < ($_this.outerHeight() - options.frameHeight + 2*pseudoInput.height())){
                            $_this.animate({
                                'bottom': t + discretTouch
                            }, 100);
                            $_this.siblings('.carrer_top').removeClass('hidden_carret');
                        } else {
                            $_this.css({
                                'bottom': ($_this.outerHeight() - options.frameHeight + 2*pseudoInput.height())
                            });
                            $_this.siblings('.carrer_bottom').addClass('hidden_carret');
                        }
                        startPageY = currentPageY = finalPageY = 0;
                    } else {
                        if( finalPageY < -defaults.minDrag ){
                            if(t - discretTouch > 0){
                                $_this.animate({
                                    'bottom': t - discretTouch
                                }, 100);
                                $_this.siblings('.carrer_bottom').removeClass('hidden_carret');
                            } else {
                                $_this.css({
                                    'bottom': 0
                                });
                                $_this.siblings('.carrer_top').addClass('hidden_carret');
                            }
                            startPageY = currentPageY = finalPageY = 0;
                        }
                    }
                }
            });
            
            /*-----------mouse scroll------------*/
             $this.find('ul').mousewheel(function (event, scrollDelta){
                var $_this = $(this);
                discretScroll = pseudoInput.height();
                var c = parseInt($_this.css('bottom'));
                if(scrollDelta > 0){
                    if(c - discretScroll > 0){
                        event.preventDefault();
                        $_this.animate({
                            'bottom': c - discretScroll
                        }, 100);
                        $_this.siblings('.carrer_bottom').removeClass('hidden_carret');
                    } else {
                        $_this.css({
                            'bottom': 0
                        });
                        $_this.siblings('.carrer_top').addClass('hidden_carret');
                    }
                } else {
                    if((c + discretScroll) < ($_this.outerHeight() - options.frameHeight + 2*pseudoInput.height())){
                        event.preventDefault();
                        $_this.animate({
                            'bottom': c + discretScroll
                        }, 100);
                        $_this.siblings('.carrer_top').removeClass('hidden_carret');
                    } else {
                        $_this.css({
                            'bottom': ($_this.outerHeight() - options.frameHeight + 2*pseudoInput.height())
                        });
                        $_this.siblings('.carrer_bottom').addClass('hidden_carret');
                    }
                }
             });
            
            /*width checkboxes*/
            $this.find('label').on('click', function(e){
                e.preventDefault();
                $(this).prev('input').prop('checked', function(i, val){
                    return !val;
                });
                e.stopPropagation();
            });
            
            return this;
        };
    });
})(jQuery);