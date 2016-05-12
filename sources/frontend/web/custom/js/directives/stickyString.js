/**
 * Created by jarik on 5/19/15.
 */


dineinApp.directive('stickyString', function ($rootScope) {
    return {
        restrict: 'E',
        replace: true,
        link: function (scope, element, attrs) {
            scope.static = false;
            $(element)
                 .on('mouseover', function () {
                     var height = $("body").height();
                     !scope.static && $("body,html").animate({"scrollTop": height}, 2000);
                 })
                 .on('mouseleave', function () {
                     $("body,html").stop();
                 });
            $(window).scroll(function(){
                var howScroll = $(this).scrollTop();
                var footerTop = $('body').outerHeight() - $(window).height();
                if((footerTop - howScroll + 60) <= $('footer').outerHeight()){
                    scope.$apply(function(){
                        scope.static= true;
                    });
                } else {
                    scope.$apply(function(){
                        scope.static= false;
                    });
                }
            });
        },
        template: '<div class="sticky_parent"><div class="sticky_string" ng-class="{static:static}"></div></div>'
    }
});