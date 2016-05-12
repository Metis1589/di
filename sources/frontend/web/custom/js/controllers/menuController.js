dineinApp.controller('menuController', function($scope, $rootScope, $location, $filter, ngProgress, restaurantService, orderService, $controller) {

    var restaurant_id = $('#restaurant_id').val();
    $scope.selectedItem = null;
    $scope.hasMenus = true;

    $scope.setMenuItem = function(menuItem, isMobile) {
        if (isMobile) {
            $('.plus.only_mobile').off();
        }
        orderService.setOrderItem(restaurant_id, null, menuItem.id, menuItem.options.length > 0, menuItem.quantity, menuItem.web_price, menuItem.name_key, menuItem.selected_options, menuItem.special_instructions)
            .success(function(data) {
            });
        //close overlay
        $('.menu_popup').click();
        $scope.endItemRender();
        $scope.selectedItem = null;
    };

    $scope.isMenuItemValid = function(menuItem) {
        return orderService.isMenuItemValid(menuItem);
    };

    $scope.clearMenuOptions = function(menuItem) {
        menuItem.selected_options = [];
    };

    $scope.selectedItemWebPrice = function() {
       return orderService.getItemPriceWithOptions($scope.selectedItem);
    };

    ngProgress.start();

    restaurantService.getMenus(restaurant_id)
        .success(function (data) {
            ngProgress.complete();

            $scope.hasMenus = data.menus.length ? true : false;
            $scope.menus = data.menus;
            $scope.currency_symbol = data.currency_symbol;
            orderService.menus = $scope.menus;

            console.log(data);
        })
        .error(function (status_code, error_message) {
            ngProgress.complete();
            $scope.error = error_message;
        });

    $scope.selectMenuItem = function(item) {
        $scope.selectedItem = angular.copy(item);
        $scope.selectedItem.quantity = 1;
        console.log('selected', $scope.selectedItem);
    };

    $scope.endItemRender = function() {
        $('.menu_hover').magnificPopup({
            mainClass   : 'menu_popup',
            showCloseBtn: false,
            callbacks   : {
                open: function() {
                    $('html, body').addClass('hidden-body');
                },
                close: function(){
                    $('html, body').removeClass('hidden-body');
                }
            }
        });
        $('.menu_hover').unbind('mfpClose');
        $('.menu_hover').bind('mfpClose', function() {
            $('menu-option').hide();
        });

        $('.plus.only_mobile').magnificPopup({
            mainClass   : 'menu_popup',
            showCloseBtn: false,
            callbacks   : {
                open: function() {
                    $('html, body').addClass('hidden-body');
                },
                close: function(){
                    $('html, body').removeClass('hidden-body');
                }
            }
        });

        $('.plus.only_mobile').unbind('mfpClose');
        $('.plus.only_mobile').bind('mfpClose', function() {
            $('menu-option').hide();
        });
    };
});
