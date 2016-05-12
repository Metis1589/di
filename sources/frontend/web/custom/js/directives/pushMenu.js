/**
 * Created by jarik on 5/27/15.
 */


dineinApp.directive('pushMenuMain',function($rootScope,$timeout){
    return {
        restrict:'A',
        controller:function($element){
            $element.addClass('pushmenu-push-toright');

            var winWidth = $(window).width();
            var winHeight = $(window).height();
//            $('.pushmenu-push-inner').css({height: Math.round(winHeight)-40+'px'});
            var closeMenu = function (){
                if($element.hasClass('push-menu-opened')){
                    $element.removeClass('push-menu-opened');
                    $element.css({ 'left': 0});
                    $rootScope.$emit('PUSH_MENU_CLOSED');
                }
            };
            var openMenu = function(){
                winWidth = $(window).width();
                if(!$element.hasClass('push-menu-opened')){
                    $element.addClass('push-menu-opened');
                    $('nav:not(.pushmenu)').css({ width: winWidth });
                    $rootScope.$emit('PUSH_MENU_OPENED');
                }
            };
            this.menuTrigger = function(){
                if (!$element.hasClass('push-menu-opened')){
                    openMenu();
                } else {
                    closeMenu();
                }
            };
            $rootScope.$on('WINDOW_RESIZED',function(event,args){
                winWidth = args.width;
                winHeight = args.height;
                $timeout(function(){
                    $('nav:not(.pushmenu)').css({ width: args.width+'px' });
//                    $('.pushmenu-push-inner').css({height: args.height-40+'px'});

                },0,false);
            });
            $rootScope.$on('LOGGED_IN',closeMenu);
            $rootScope.$on('LOGGED_OUT',closeMenu);
        }
    }
});
dineinApp.directive('pushMenuLeft',function($rootScope,$timeout,isMobile){
    return {
        require:'^pushMenuMain',
        restrict:'A',
        link:function(scope,elem,attrs,ctrl){
            var winWidth = $(window).width();
            elem.css({width: winWidth+'px', 'left': - winWidth+'px'});
            elem.children('.pushmenu-push-inner')
                .css({right: winWidth+'px', 'left': - winWidth+'px'});
            $rootScope.$on('PUSH_MENU_OPENED',function(){
                var menuWidth = $(window).width();
                if(!elem.hasClass('pushmenu-open')){
                    elem.addClass('pushmenu-open').css({ 'left': 0});
                    elem.children('.pushmenu-push-inner')
                        .css({ 'left': 0})
                        .css({ 'right': 0});
                }
            });
            $rootScope.$on('PUSH_MENU_CLOSED',function(){
                if(elem.hasClass('pushmenu-open')){
                    winWidth = $(window).width();
                    elem.removeClass('pushmenu-open').css({ 'left': - winWidth+'px'});
                    elem.children('.pushmenu-push-inner')
                        .css({ 'left': - winWidth + 'px'})
                        .css({ 'right': winWidth + 'px'});
                }
            });
            $rootScope.$on('WINDOW_RESIZED',function(event,args){
                winWidth = args.width;
                $timeout(function(){
                    //debugger;
                    if(!elem.hasClass('pushmenu-open')){
                        elem.css({width: winWidth+'px', 'left': - winWidth+'px'});
                        elem.children('.pushmenu-push-inner')
                             .css({right: winWidth+'px', 'left': - winWidth+'px'});
                    }else{
                        elem.css({width: winWidth+'px'});
                    }
                },0,false);
            });
            elem.on('click',function(e){
                if(isMobile && elem.hasClass('pushmenu-open')){
                    ctrl.menuTrigger();
                }
            });
        }
    }
});
dineinApp.directive('pushMenuButton',function($rootScope){
    return {
        require:'^pushMenuMain',
        restrict:'A',
        link:function(scope,elem,attrs,ctrl){
            elem.on('click',function(event){
                if (!elem.hasClass('back-button')) {
                    elem.addClass('active');
                    ctrl.menuTrigger();
                }
            });
        }
    }
});

