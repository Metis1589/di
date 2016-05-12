dineinApp.controller('cartController', function($scope, $rootScope, $location, $window, $element, $filter, ngProgress, orderService, userService, $timeout, restaurantService ) {

    var restaurant_id = $('#restaurant_id').val();
    $scope.selectedItem = null;
    $scope.eta = '';
    $scope.driver_charge = 1;

    $scope.$watch(
        function () {
            return restaurantService.getSavedMenus();
        },
        function (newValue, oldValue) {
            if (newValue != null) {
                $scope.menus = newValue.menus;
            }
        });

    $scope.init = function() {
        orderService.getOrder(restaurant_id)
        .success(function (data) {
            $scope.currency_symbol = data.currency_symbol;
            $scope.min_order_value = data.min_order_value;
            $scope.max_order_value = data.max_order_value;
            $scope.min_order_amount = data.min_order_amount;
            $scope.max_order_amount = data.max_order_amount;
            $scope.is_available_for_time = data.is_available_for_time;
            $scope.restaurant_id = data.restaurant_id;
            $scope.eta = data.delivery_time;
            $scope.driver_charge = data.driver_charge;
            $timeout(function() {
                $scope.cart = orderService.getCart();
                $scope.initTipSelect();
            });
        })
        .error(function (status_code, error_message) {
            ngProgress.complete();
            $scope.error = error_message;
        });
    };    
    
    $scope.filterDeleted = function(item) {
        return item.quantity > 0;
    };

    $scope.add = function(orderItem) {
        //orderItem.quantity++;
        //calculateTotal();
        orderService.setOrderItem($scope.restaurant_id, orderItem.id, orderItem.menu_item_id, false, orderItem.quantity + 1);
    };

    $scope.subtract = function(orderItem) {
        //orderItem.quantity--;
        //calculateTotal();
        orderService.setOrderItem($scope.restaurant_id, orderItem.id, orderItem.menu_item_id, false, orderItem.quantity - 1);
    };

    $scope.drop = function(orderItem) {
        //orderItem.quantity--;
        //calculateTotal();
        orderService.setOrderItem($scope.restaurant_id, orderItem.id, orderItem.menu_item_id, false, 0);
    };

    $scope.getItemPrice = function(item) {
        return orderService.getItemPriceWithOptions(item);
    };

    $scope.showCartData = function() {
        $('#nav_list').addClass('back-button');
        $rootScope.$broadcast('scanner-started', { view_plate: true });
    };

    $scope.toCheckout = function() {
        //alert('ready to checkout');

        if (orderService.getCart().is_valid) {
            $window.location.href = "/order/checkout";
        }
        else {
            alert(orderService.getCart().validate_error);
        }
    };

    $scope.setVoucher = function() {
        $scope.voucher_error = null;
        orderService.setVoucher($scope.cart.voucher_code)
            .success(function (data) {

            })
            .error(function (status_code, error_message) {
                $scope.voucher_error = error_message;
            });
    };

    $scope.setDriverCharge = function(charge) {
        $scope.driver_charge = charge;
        orderService.setDriverCharge(parseFloat($scope.driver_charge))
            .success(function (data) {

            })
            .error(function (status_code, error_message) {
                $scope.error = error_message;
            });
    };

    $scope.selectMenuItem = function(orderItem) {
        for(var i = 0; i < $scope.menus.length; i++) {
            var menu = $scope.menus[i];
            for(var j = 0; j < menu.menuCategories.length; j++) {
                var category = menu.menuCategories[j];
                for (var k = 0; k < category.menuItems.length; k++) {
                    var item = category.menuItems[k];
                    if (item.id == orderItem.menu_item_id.toString()) {
                        $scope.selectedItem = angular.copy(item);
                        break;
                    }
                }
            }
        }
        $scope.selectedItem.selected_options = [];
        if (orderItem.selected_options != null) {
            for(var i = 0; i < orderItem.selected_options.length; i++) {
                var orderOption = orderItem.selected_options[i];
                $scope.selectedItem.selected_options.push({
                    quantity: orderOption.quantity,
                    option: orderOption.option.menu_option != null ? orderOption.option.menu_option : orderOption.option
                });
            }
        }

        $scope.selectedItem.quantity = orderItem.quantity;
        $scope.selectedItem.order_item_id = orderItem.id;
        $scope.selectedItem.special_instructions = orderItem.special_instructions;

        console.log('selectMenuItem', $scope.selectedItem);

    };

    $scope.selectedItemWebPrice = function() {
        return orderService.getItemPriceWithOptions($scope.selectedItem);
    }

    $scope.isMenuItemValid = function(menuItem) {
        return orderService.isMenuItemValid(menuItem);
    }

    $scope.setMenuItem = function(menuItem) {
        orderService.setOrderItem($scope.restaurant_id, menuItem.order_item_id, menuItem.id, menuItem.options.length > 0, menuItem.quantity, menuItem.web_price, menuItem.name_key, menuItem.selected_options, menuItem.special_instructions)
            .success(function(data) {

            });
        //close overlay
        $('.menu_popup').click();
        $scope.selectedItem = null;
    };

    $scope.cartItemsRendered = function() {
        $('.cart_hover').magnificPopup({
            mainClass: 'menu_popup',
            showCloseBtn: false
        });
        $('.cart_hover').unbind('mfpClose');
        $('.cart_hover').bind('mfpClose', function() {
            $('menu-option').hide();
        });
    };
    $scope.$on('DELIVERY_CHARGE_UPDATED',function(event,args){
        $scope.cart = orderService.getCart();
    });
    $scope.initTipSelect = function(){
        $($element).find('.sidebar_select').dineinSelect({
            timeInterval: 10,
            frameHeight : 140,
            onlyOne     : true
        });
    };

    $rootScope.$on('DELIVERY_INFO_CHANGED', function () {
        $scope.init();
    });
});