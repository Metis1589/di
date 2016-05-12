dineinApp.controller('registrationController', function($scope, userService, ngProgress, $window) {
    $scope.registrationError = '';
    $scope.is_registered     = false;
    $scope.is_mail_sent      = false;
    $scope.loggedin          = userService.isLoggedIn();

    if ($scope.loggedin) {
        $window.location = '/';
    }

    $scope.register = function() {
        ngProgress.start();
        userService.register($scope.title, $scope.first_name, $scope.last_name, $scope.address1, $scope.address2, $scope.city, $scope.postcode, $scope.phone, $scope.username, $scope.password)
            .success(function (data) {
                ngProgress.complete();
                $scope.registrationError = '';
                $scope.is_registered     = true;
                $scope.is_mail_sent      = true;
                ngProgress.complete();
            })
            .error(function (status_code, error_message) {
                $scope.registrationError = error_message;
                ngProgress.complete();
            });
    };

    $scope.setPasswordError = '';

    $scope.resetPassword = function() {
        ngProgress.start();
        var token = location.search.split('token=')[1];
        userService.passwordReset(token, $scope.password)
            .success(function (data) {
                ngProgress.complete();
                $scope.setPasswordError = '';
                $scope.is_password_reset = true;
                ngProgress.complete();
            })
            .error(function (status_code, error_message) {
                $scope.setPasswordError = error_message;
                $scope.is_password_reset = false;
                ngProgress.complete();
            });
    }

    $scope.activateAccount = function() {
        ngProgress.start();
        var token = location.search.split('token=')[1];
        userService.activateAccount(token)
            .success(function (data) {
                ngProgress.complete();
                $scope.is_activated = true;
                ngProgress.complete();
            })
            .error(function (status_code, error_message) {
                $scope.is_activated = false;
                $scope.has_activation_error = true;
                ngProgress.complete();
            });
    }

    $scope.activateAccount();
});
