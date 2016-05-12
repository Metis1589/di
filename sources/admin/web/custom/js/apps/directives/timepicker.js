
dineinApp.directive('timepicker', function() {
    return {
        restrict: 'A',
        require : 'ngModel',
        link : function (scope, element, attrs, ngModelCtrl) {
            $(function(){
                $(element).on('change', function() {
                    scope.$apply(function () {
                        ngModelCtrl.$setViewValue($(element).val());
                    });
                })
            });
        }
    }
});
