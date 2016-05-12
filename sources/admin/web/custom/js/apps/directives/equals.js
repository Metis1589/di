
dineinApp.directive('equals', function() {
    return {
        require: 'ngModel',
        link: function (scope, elem, attrs, model) {
            if (!attrs.equals) {
                console.error('equals expects a model as an argument!');
                return;
            }
            scope.$watch(attrs.equals, function (value) {
                var isValid = value === model.$viewValue;
                model.$setValidity('equals', isValid);
            });
            model.$parsers.push(function (value) {
                var isValid = value === scope.$eval(attrs.equals);
                model.$setValidity('equals', isValid);
                return isValid ? value : undefined;
            });
        }
    };
});