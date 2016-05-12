
dineinApp.directive("repeatEnd", function(){
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            if (scope.$last) {
                setTimeout(function(){
                    scope.$eval(attrs.repeatEnd);
                }, 100);

            }
        }
    };
});