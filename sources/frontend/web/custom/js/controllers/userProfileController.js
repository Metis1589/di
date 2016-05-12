


dineinApp.controller('userProfileController', function($scope, $filter, userService, $location, ngProgress, restaurantService, $window, orderService, isMobile) {
    $scope.isMobile = isMobile;
    $scope.tab = 'addresses';
    var pathTabAlias = {
        '/membership': 'membership',
        '/addresses': 'addresses',
        '/loyality-points': 'loyalityPoints',
        '/my-orders': 'pastOrders',
        '/my-reviews': 'reviews'
    };
    $scope.$on('$locationChangeSuccess',function(){
        var hash = $location.path();
        if(hash in pathTabAlias){
            $scope.tab = pathTabAlias[hash];
        }
    });
    /* Create new order form begin */
    if(!restaurantService.isValidDeliveryType()){
        restaurantService.setDeliveryTime(null);
        restaurantService.setDeliveryDate(null);
        restaurantService.setDeliveryType(null);
    }
    $scope.delivery_type = restaurantService.getDeliveryType();
    if($scope.delivery_type == 'undefined' || $scope.delivery_type == undefined || $scope.delivery_type== 'null'){
        $scope.delivery_type = null;
    }
    $scope.delivery_date = restaurantService.getDeliveryDate();
    $scope.delivery_time = restaurantService.getDeliveryTime();
    $scope.postcode = restaurantService.getPostcode();

    $scope.setDelivery = function(data){
        $scope.delivery_type = data.deliveryType;
        $scope.delivery_date = data.deliveryDate;
        $scope.delivery_time = data.deliveryTime;
    };

    $scope.submitNewOrder = function($event){
        if($scope.newOrderForm.$valid){
            restaurantService.setDeliveryType($scope.delivery_type);
            restaurantService.setDeliveryDate($scope.delivery_date);
            restaurantService.setDeliveryTime($scope.delivery_time);
            restaurantService.setPostcode($scope.postcode);
            $window.location.href = $scope.searchUrl;
        }
    };
    /* Create new order form end */
    if($location && $location.path() && $location.path() in pathTabAlias){
        $scope.tab = pathTabAlias[$location.path()];
    }
    /* membership begin */

    $scope.saveProfileError = '';

    $scope.saveProfile = function() {

        ngProgress.start();

        userService.setProfile($scope.profile)
            .success(function (data) {
                $scope.saveProfileError = '';
                ngProgress.complete();
            })
            .error(function (status_code, error_message) {
                $scope.saveProfileError = error_message;
                ngProgress.complete();
            });
    };

    userService.getProfile()
        .success(function (data) {
            $scope.profile = data;
        })
        .error(function (status_code, error_message) {
            window.location = '/';
        });

    /* membership end */

    /* addresses begin */

    $scope.address = {};
    $scope.addressSaveError = '';
    userService.getAddresses()
        .success(function (data) {
            $scope.addresses = data;
            $scope.emptyForm = angular.copy($scope.address);
             if(!isMobile){
                 $scope.address_selected( data[0].id);
             }
            //console.log('get Address',$scope.addresses, $scope.address);
        });

    $scope.address_selected = function(id) {
        $scope.tab = 'addresses';
        if(id !== 0){
            // Select address
            $scope.address = angular.copy($filter('filter')($scope.addresses, {id: id})[0]);
        }else{
            // Create address
            $scope.address = angular.copy($scope.emptyForm);
            $scope.address.id = 0;
           // $scope.address.email = $scope.profile.email;
           // $scope.address.postcode = restaurantService.getPostcode();
        }
        //close overlay
        $('.mask_popup').click();
    };

    $scope.menu_selected = function(tab){
        $scope.tab = tab;
        $('.mask_popup').click();
    };

    $scope.saveAddress = function() {
        ngProgress.start();
        userService.saveAddress($scope.address.id, $scope.address).success(function(data) {
            ngProgress.complete();
            if($scope.address.id == null){
                var newAddr = data.filter(function(a){return $filter('filter')($scope.addresses, {id: a.id}).length == 0;});
                $scope.addresses = data;
                if(isMobile){
                    $scope.address = angular.copy($scope.emptyForm);
                    $scope.address.id = null;
                }else{
                    newAddr.length == 1 && $scope.address_selected(newAddr.shift().id);
                }
            }else{
                $scope.addresses = data;
                if(isMobile){
                    $scope.address = angular.copy($scope.emptyForm);
                    $scope.address.id = null;
                }
            }
            $scope.is_saved = true;
            $scope.addressSaveError = '';
        }).error(function (status_code, error_message) {
            ngProgress.reset();
            $scope.addressSaveError = error_message;
        });
    };
    $('body').on('click',function(e){
        !$(e.target).hasClass('link_menu')
        && !$(e.target).hasClass('mask_modal')
        && $('.menus.link_menu.active_item').hasClass('open')
        && $('.mask_popup').click();
    });
    /* addresses end */

    /* orders begin */
    $scope.reorder = function(order) {
        orderService.reorder(order.id)
            .success(function(data) {
                $window.location.href = "/restaurant/view?id=" + order.restaurant_id;
            })
    };

    userService.getOrders()
        .success(function (data) {
            $scope.orders = data;
        })
        .error(function (status_code, error_message) {

        });
    /* orders end */

    /* reviews */

    $scope.reviews = {};

    userService.getReviews()
        .success(function (data) {
            $scope.reviews = data;
        });

    /* /reviews */
});