;(function ($, undefined) {

    String.prototype.repeat = function(n) {
        n = n || 1;
        return Array(n + 1).join(this);
    };

    $(document).ready(function () {
        'scrollBy' in window && window.scrollBy(0,1);
        var isMobile = $(window).width() < 1100;

        function fullWrapper() {
            $('.restaurant_page .wrapper').css('minHeight', parseInt($(window).height()) - 85);
        }

        fullWrapper();

        function fullImage(){
            var windHeight  = $(window).height() - 60
              , wrappHeight = $('section.main .wrapper').height();

            $('section.main .wrapper').css({
                paddingTop   : (windHeight - wrappHeight) / 2,
                paddingBottom: (windHeight - wrappHeight) / 2
            });
        }

        fullImage();

        $('.select_filter').dineinSelect({
            timeInterval: 10,
            frameHeight : 170,
            onlyOne     : true
        });

        $('.yes_no').dineinSelect({
            timeInterval: 10,
            frameHeight: 140,
            onlyOne    : true
        });

        $('.date_time').dineinSelect({
            timeInterval: 10,
            frameHeight : 90,
            onlyOne     : true
        });

        $('.sidebar_select').dineinSelect({
            timeInterval: 10,
            frameHeight : 140,
            onlyOne     : true
        });

        $('.asap_select').dineinSelect({
            timeInterval: 1,
            frameHeight : 100,
            onlyOne     : true
        });

        $('.delivery_location').dineinSelect({
            timeInterval: 1,
            frameHeight : 200
        });

        var touchMoveListener = function (event) {
            event.preventDefault();
        };

        $('.popup-modal').magnificPopup({
            showCloseBtn: false,
            mainClass   : 'info_mobile info_mobile_login',
            callbacks   : {
                open: function() {
                    // Disable touchmove event when login popup openned
                    $(this.content).is('#test-modal')
                    && 'addEventListener'
                    && document.body.addEventListener('touchmove', touchMoveListener, false);
                    $('.main_page_menu').css({
                        'z-index': 0
                    });
                    isMobile && $('body').css({overflow:'hidden'});
                    $('html, body').addClass('hidden-body');
                },
                close: function(){
                    // enable touchmove event when login popup openned
                    $(this.content).is('#test-modal')
                    && 'removeEventListener'
                    && document.body.removeEventListener('touchmove', touchMoveListener, false);

                    $('.main_page_menu').css({
                        'z-index': 2005
                    });
                    isMobile && $('body').css('overflow') === 'hidden' && $('body').css({overflow:'auto'});
                    $('html, body').removeClass('hidden-body');
                }
            }
        });

        $(document).on('click', '.popup-modal-dismiss', function (e) {
            e.preventDefault();
            $.magnificPopup.close();
        });

        $('.popup_info').magnificPopup({
            mainClass   : 'sidebar_popup',
            showCloseBtn: false,
            callbacks   : {
                open: function() {
                    $('html, body').addClass('hidden-body');
                },
                close: function(){
                    $('html, body').removeClass('hidden-body');
                }
            }
        });

        $('.link_asap, .link_filter').magnificPopup({
            showCloseBtn: false,
            mainClass   : 'info_mobile',
            callbacks   : {
                open: function(){
                    $('html, body').addClass('hidden-body');
                    $('.mfp-bg').hide();
                },
                close: function(){
                    $('html, body').removeClass('hidden-body');
                }
            }
        });

        $('.cg_order, .yg_order, .load_link').magnificPopup({
            showCloseBtn: false,
            callbacks   : {
                open: function() {
                    $('html, body').addClass('hidden-body');
                },
                close: function(){
                    $('html, body').removeClass('hidden-body');
                }
            }
        });

        $('.restaurant_index').magnificPopup({
            showCloseBtn  : false,
            closeOnBgClick: false,
            mainClass     : 'restaurant_popup',
            callbacks   : {
                open: function() {
                    $('html, body').addClass('hidden-body');
                },
                close: function(){
                    $('html, body').removeClass('hidden-body');
                }
            }
        });
        $('.restaurant_index').trigger('click');

        $('.apply_changes').on('click', closePopup);

        $('.menu_item_instructions').click(function () {
            $(this).toggleClass('opened');
        });

        $('.menu_item_instructions textarea').click(function (e) {
            e.stopPropagation();
        }).change(function () {
            $('.menu_item_instructions').toggleClass('selected', !!$(this).val());
        });

        /*-------payment text---------*/
        $('.sticky_text').css({
            'min-height': $(window).height() - 150
        });

        /*----fullscreen image----*/
        window.onload = function() {
            fullImage();
        };

        window.onresize = function(){
            isMobile = $(window).width() < 1100;
            fullImage();
            fullWrapper();
        };

        /*---scroll to footer---*/
        $('.inner_page_sticky_string').on('mouseenter', function() {
            if (!$(this).hasClass('static')) {
                var height = $("body").height();
                $("body, html").animate({"scrollTop":height}, 2000);
            }
        }).on('mouseleave', function(){
            $("body,html").stop();
        });

        /*inner page*/
        $(window).scroll(function(){
            var howScroll  = $(this).scrollTop()
              , windHeight = $(window).height()
              , bodyHeight = $('body').height();

            $('.inner_page_sticky_string').toggleClass('static', !!((bodyHeight - windHeight) < howScroll));
        });
    });

    function setDeliveryMenuItem(itemSelector) {
        $('.delivery_asap>' + itemSelector).show().siblings().hide();
    }

    function closePopup() {
        $.magnificPopup.close();
    }

    $('.info_message').on('focus', function() {
        var el = $(this);
        if (el.hasClass('contact')) {
            if (el.val() === 'MESSAGE*') {
                el.val('').css('textAlign', 'left');
            }
        } else if (el.hasClass('review')) {
            if (el.val() === 'REVIEW*') {
                el.val('').css('textAlign', 'left');
            }
        } else {
            // DINE-3189
            if (el.is('.checkout_page .info_message')) {
                el.prop('placeholder','')
                    .css('textAlign', 'left')
                    .css('padding',   '0');
            }
        }
    }).on('blur', function() {
        var el = $(this);
        if (el.hasClass('contact')) {
            if (el.val() === '') {
                el.val('MESSAGE*')
                    .css('textAlign', 'center')
                    .css('padding',   '5px');
            }
        } else if (el.hasClass('review')) {
            if (el.val() === '') {
                el.val('REVIEW*')
                    .css('textAlign', 'center')
                    .css('padding',   '5px');
            }
        } else {
            // DINE-3189
            if (el.is('.checkout_page .info_message') && el.val().length == 0) {
                el.prop('placeholder',el.data('initial-placeholder'))
                     .css('textAlign', 'center')
                     .css('padding',   '30px 0px');
            }
        }
    });

    $('document').ready(function() {
        $('.contact.info_message').val('MESSAGE*');
        $('.review.info_message').val('REVIEW*');
    });

    $('.mfp-container, .restaurant_popup').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        return false;
    });

    function onResize() {
        $(window).trigger('resize');
    }

    //var timer;
    //$(window).bind('resize', function() {
    //   timer && clearTimeout(timer);
    //   timer = setTimeout(onResize, 10);
    //    var width = $(window).width();
    //    if (width !== ($('.pushmenu-left').width() + 20)) {
    //        $('.pushmenu-left, .pushmenu-push-inner')
    //            .css('width', width + 'px')
    //            .css('left', '-' + width + 'px');
    //    }
    //});

    //$(window).on('scroll, resize, touchstart, touchmove, mousemove', function() {
    //    $('.mfp-container, .mfp-wrap')
    //        .css('top', '0')
    //        .css('left', '0')
    //        .css('right', '0')
    //        .css('bottom', '0')
    //        .css('height', '100%');
    //});

    // Content pages FAQ
    // Modify anchors hash to angular capability

    $('.faq a[id][name]').each(function() {
        var name = $(this).attr('name');
        $('.faq a[href="#'+name+'"]').attr('href','#/'+name);
        $(this).attr('name','/'+ name);
    });

    function activateFaqLinks() {
        $('a[id][name]').removeClass('active');
        $('.faq .wrapper ul:first-child > li > a.active').removeClass('active');
        $('.faq a[href="' + window.location.hash + '"]').addClass('active');
        $('.faq a[name="' + window.location.hash.replace('#', '') + '"]').addClass('active');
    }

    activateFaqLinks();

    $(window).on('hashchange', function() {
        activateFaqLinks();
    });
    $('.inner_page_modal, nav, .menu-modal, .white-popup-block, .bottom_buttons_set').on('touchmove', function(e) {
        e.preventDefault();
    }, false);
    $('.form_login, .pushmenu-push-inner, .select_box').on('touchmove', function(e) {
        e.stopPropagation();
    });
})(jQuery);