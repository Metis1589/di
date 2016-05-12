dineinApp.directive('timeBoth', function() {
    return {
        require: 'ngModel',
        link: function (scope, elem, attrs, model) {
            if (!attrs.timeBoth) {
                console.error('timeBoth expects a model as an argument!');
                return;
            }
            var validate = function(value, compareValue) {
                var isValid = true;

                if ((value == null || value == '') && (compareValue != null && compareValue != '')) {
                    isValid = false;
                }
                if ((value != null && value != '') && (compareValue == null || compareValue == '')) {
                    isValid = false;
                }
                return isValid;
            };
            scope.$watch(attrs.timeBoth, function (value) {
                var isValid = validate(model.$viewValue, value);
                model.$setValidity('timeboth', isValid);
            });
            model.$parsers.push(function (value) {
                var isValid = validate(value, scope.$eval(attrs.timeBoth));
                model.$setValidity('timeboth', isValid);
                return value;
            });
        }
    };
});