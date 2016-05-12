dineinApp.directive('validationSummary', function($filter) {
    return {
        restrict: 'E',
        templateUrl: 'validation-summary',
        scope: {
            form: '=formName',
            customError: '=customError',
            formId: '@',
            errors: '@'
        },
        link: function($scope, $element, $attrs, $ctrl) {
            $scope.isFormInvalid = function() {
                var isValid = true;
                $scope.errors = [];
                for(var errorType in $scope.form.$error) {
                    var invalidElCount = $filter('filter')($scope.form.$error[errorType], function(e){
                        var input = $('[name="'+ e.$name +'"]', '#' +$scope.formId);
                        if (input.is(':focus')) {
                            return false;
                        }
                        var isInvalid = e.$invalid && e.$dirty;

                        if (isInvalid) {
                            $scope.errors.push(input.attr('err-'+errorType));
                        }

                        return isInvalid;
                    }).length;
                    if (!isValid) {
                        return true;
                    }

                    isValid = isValid && (invalidElCount == 0);
                }

                return !isValid || $scope.customError != '';
            };
        }
    };
});