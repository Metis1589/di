dineinApp.controller('loginController', function ($scope, $location, userService, ngProgress, $rootScope,restaurantService) {

    $scope.loggedin = userService.isLoggedIn();
    $scope.loginActive = true;
    $scope.showLogin = function () {
        $('.popup-modal').magnificPopup({
            type: 'inline',
            preloader: false,
            focus: '#username',
            modal: true
        });
        $scope.error = null;
    };

    $scope.closePopup = function($event){
        $scope.loginActive = true;
        $event.stopPropagation && $event.stopPropagation();
        $.magnificPopup.close();
    };
    $scope.$on('userMenuTrigger',function($event,args){
        $scope.closePopup($event);
    });
    $scope.loginAction = function () {
        ngProgress.start();
        userService.login($scope.username, $scope.password, $scope.is_remember)
            .success(function (data) {
                userService.getAddresses().then(function(addresses){
                    if(addresses && addresses.length >=1 ){
                        var address = addresses.shift();
                        restaurantService.setPostcode(address.postcode)
                    }
                    ngProgress.complete();
                    $scope.loggedin = true;
                    $rootScope.loggedin = true;
                    $scope.password = $scope.username = '';
                    if(window.location.pathname=='/site/activate' || window.location.pathname=='site/reset-password'){
                        window.location.href = document.location.origin;
                    }
                    else {
                        $.magnificPopup.close();
                        document.location.reload();
                    }
                });

            })
            .error(function (status_code, error_message) {
                ngProgress.complete();
                $scope.error = error_message;
            });
    };

    $scope.showLoginFormAction = function(){
        $scope.loginActive = true;
    };
    $scope.showPasswordResetFormAction = function () {
        $scope.loginActive = false;
    };

    $scope.requestPasswordResetAction = function () {

        ngProgress.start();

        userService.requestPasswordReset($scope.reset_username)
            .success(function (data) {
                ngProgress.complete();
                $scope.reset_error = null;
                $scope.reset_complete = true;
                $.magnificPopup.close();
                $scope.loginActive = true;
            })
            .error(function (status_code, error_message) {
                ngProgress.complete();
                $scope.reset_error = error_message;
                $scope.reset_complete = false;
            });
    };

});
