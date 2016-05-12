dineinApp.controller('checkoutController', function($scope, $location, $filter, orderService, ngProgress, userService, $window, restaurantService, $timeout, $element) {

    $scope.billing_address = {
        postcode:restaurantService.getPostcode(),
        email:userService.getEmail()
    };
    $scope.delivery_address = {
        postcode:restaurantService.getPostcode(),
        email:userService.getEmail()
    };
    $scope.is_logged_in = userService.isLoggedIn();
    $scope.delivery_type = restaurantService.getDeliveryType();
    $scope.cart = orderService.getCart();
    $scope.orderCheckoutError = '';
    $scope.paymentResult = $window.location.search.split('payment_result=')[1];
    $scope.corporateInfo = null;
    $scope.$watch(
        function () {
            //console.log('update');
            return restaurantService.getDeliveryType();
        },
        function (newValue, oldValue) {
            $scope.delivery_type = newValue;
        });

    $scope.showPanel = function(panel) {
        $scope.panel = panel;
    };

    $scope.getCorporateUsers = function() {
        userService.getCorporateUsers($scope.corporateInfo != null ? $scope.corporateInfo.selectedExpenseType.id : null).success(function (data) {
            if (Object.keys(data).length > 0 && data.userGroup.activeExpenseTypes.length > 0) {
                $scope.corporateInfo = data;
            }
        });
    };

    if (userService.isLoggedIn()) {

        $scope.getCorporateUsers();

        userService.getAddresses()
            .success(function (data) {
                 var emptyNameCounter = 1;
                 for(var key in data){
                    if(data[key].name==null){
                        data[key].name = 'ADDRESS LINE '+emptyNameCounter;
                        emptyNameCounter++;
                    }
                 }
                $scope.saved_addresses = data;

                $scope.saved_addresses.splice(0, 0, {});

                //$scope.delivery_address.selected = $scope.saved_addresses[0];

                $scope.billing_address.selected = $scope.saved_addresses[0];

                 // fill fields
                 $scope.billing_address.email = $scope.delivery_address.email = userService.getEmail();

            });
    }

    $scope.checkout = function() {
        ngProgress.start();

        orderService.checkout($scope.additional_requirements, $scope.include_utensils, $scope.delivery_address, ($scope.billing_address_type == 1 ? null : $scope.billing_address))
            .success(function (data) {
                ngProgress.complete();
                $window.location = data.url != '' ? data.url : '/order/tracker?order_number=' + data.order_number;

                $scope.orderCheckoutError = '';
            })
            .error(function (status_code, error_message) {
                ngProgress.complete();
                $scope.orderCheckoutError = error_message;
            });

        //$window.location.href="/order/checkout"
    };
    $scope.$watch(function(){
        return $scope.delivery_address.selected;
    },function(newValue){
        newValue && $scope.delivery_address_selected();
    });
    $scope.$watch(function(){
        return $scope.billing_address.selected;
    },function(newValue){
        newValue && $scope.billing_address_selected();
    });
    $scope.delivery_address_selected = function() {
        $scope.delivery_address.title = $scope.delivery_address.selected.title;
        $scope.delivery_address.first_name = $scope.delivery_address.selected.first_name;
        $scope.delivery_address.last_name = $scope.delivery_address.selected.last_name;
        $scope.delivery_address.address1 = $scope.delivery_address.selected.address1;
        $scope.delivery_address.address2 = $scope.delivery_address.selected.address2;
        $scope.delivery_address.city = $scope.delivery_address.selected.city;
        $scope.delivery_address.postcode = $scope.delivery_address.selected.postcode;
        $scope.delivery_address.phone = $scope.delivery_address.selected.phone;
        $scope.delivery_address.email = $scope.delivery_address.selected.email;
        $scope.delivery_address.instructions = $scope.delivery_address.selected.instructions;
        closeAddressMenu();
    };

    $scope.billing_address_selected = function() {
        $scope.billing_address.title = $scope.billing_address.selected.title;
        $scope.billing_address.first_name = $scope.billing_address.selected.first_name;
        $scope.billing_address.last_name = $scope.billing_address.selected.last_name;
        $scope.billing_address.address1 = $scope.billing_address.selected.address1;
        $scope.billing_address.address2 = $scope.billing_address.selected.address2;
        $scope.billing_address.city = $scope.billing_address.selected.city;
        $scope.billing_address.postcode = $scope.billing_address.selected.postcode;
        $scope.billing_address.phone = $scope.billing_address.selected.phone;
        $scope.billing_address.email = $scope.billing_address.selected.email;
    };

    $scope.showNewCorpUserForm = function(index, showform) {
        $scope.addUserError = null;
        if (showform) {
            $scope.showAddName = true;
        }
        $scope.newCorpUser = {
            email: null,
            firstName: null,
            lastName: null,
            company: null,
            index: index
        };
    };
    $scope.closeNewCorpUserForm =function (){
        $scope.addUserError = null;
        $scope.showAddName = false;
        $scope.newCorpUser = {
            email: null,
            firstName: null,
            lastName: null,
            company: null,
            index: null
        };
    };
    $(document).on('click',function(e){
        if($scope.showAddName == true){
            var clickedOutForm = $(e.target).parents('.add-name-form').length == 0 && !$(e.target).is('.add-name-form') && !$(e.target).is('.add-name-btn');
            if(clickedOutForm){
                $timeout(function(){$scope.closeNewCorpUserForm()},0);
            }
        }

    });

    $scope.hideNewCorpUserForm = function(index) {
        $scope.showAddName = false;
    }

    $scope.addCorpUser = function() {
        userService.addCorporateUser($scope.newCorpUser.index,$scope.newCorpUser.firstName, $scope.newCorpUser.lastName, $scope.newCorpUser.email, $scope.newCorpUser.company).success(function(data){
            $('.add-user-mobile').removeClass('opened');
            $scope.showAddName = false;
            $scope.corporateInfo = data;
        }).error(function (status_code, error_message) {
            $scope.addUserError = error_message;
        });
    }

    $scope.removeCorpUser = function(index) {
        $scope.corporateInfo.users.splice(index, 1);
        userService.removeCorporateUser(index).success(function(data){
            $scope.corporateInfo = data;
        }).error(function (status_code, error_message) {
            console.error('Remove corp user error', error_message);
        });
    }

    $scope.changeExpenseType = function(expenseTypeId) {
        $scope.corporateInfo.selectedExpenseType.id = expenseTypeId;
        $scope.getCorporateUsers();
    }

    $scope.changeCode = function(index, codeId) {
        $('.user-settings-mobile form>div.exptype_select:eq('+index+')').removeClass('opened');
        var corpUser = $scope.corporateInfo.users[index];
        console.log($scope.corporateInfo.users, index, codeId);
        corpUser.code_id = codeId;
        $scope.setCorpUserData(index);
    }


    $scope.setCorpUserData = function(index) {
        $('.user-settings-mobile').eq(index).removeClass('opened');
        var corpUser = $scope.corporateInfo.users[index];

        userService.setCorporateUserData(index, corpUser.code_id, corpUser.corp_user.allocation, corpUser.corp_user.comment).success(function($data){
        });
    }

    $scope.getCorpUserLimit = function(index) {
        var expenseType = $scope.getExpenseType(index);
        return expenseType != null ? expenseType.limit_per_order : null;
    }

    $scope.getCorpUserLimitSoft = function(index) {
        var expenseType = $scope.getExpenseType(index);
        return expenseType != null ? expenseType.soft_limit_max : null;
    }

    $scope.isCorpUserLimitSoft = function(index) {
        var expenseType = $scope.getExpenseType(index);
        return expenseType != null ? (expenseType.limit_type == 'Soft') : false;
    }


    $scope.getExpenseType = function(index) {
        //if ($scope.corporateInfo == null) {
        //    return null;
        //}
        //var expTypes = $filter('filter')($scope.corporateInfo.userGroup.activeExpenseTypes, {id: $scope.corporateInfo.selectedExpenseType.id});
        //if (expTypes != undefined && expTypes.length > 0){
        //    return expTypes[0];
        //}
        return $scope.corporateInfo.users[index].expense_type;
    }

    $scope.getSelectedCode = function(index) {
        if ($scope.corporateInfo == null) {
            return null;
        }
        var corpUser = $scope.corporateInfo.users[index];

        var codes = $filter('filter')(corpUser.user_group.activeCodes, {id: corpUser.code_id});
        if (codes != undefined && codes.length > 0){
            return codes[0];
        }
        return null;
    }

    $scope.isTotalAllocationValid = function() {
        if ($scope.corporateInfo == null || $scope.corporateInfo.has_inntouch) {
            return true;
        }
        if ($scope.toBeAllocated() < 0) {
            return false;
        }
        for(var i = 0; i < $scope.corporateInfo.users.length; i++) {
            if (!$scope.isAllocationValid(i)) {
                return false;
            }
        }
        return true;
    }


    $scope.isAllocationValid = function(index) {

        var expenseType = $scope.getExpenseType(index);
        var corpUser = $scope.corporateInfo.users[index];

        if (corpUser.corp_user.allocation == undefined || corpUser.corp_user.allocation == null || corpUser.corp_user.allocation == '') {
            return false;
        }
        if (expenseType == null) {
            return false;
        }

        if (expenseType.limit_type == 'Soft') {
            var comment = $scope.getComment(index);
            if (parseFloat(expenseType.limit_per_order) >= parseFloat(corpUser.corp_user.allocation)) {
                return true;
            }

            if (parseFloat(expenseType.soft_limit_max) >= parseFloat(corpUser.corp_user.allocation)) {
                return comment != '';
            }
            return  false;
        }
        return parseFloat(expenseType.limit_per_order) >= parseFloat(corpUser.corp_user.allocation);;
    }

    $scope.getComment = function(index) {
        var corpUser = $scope.corporateInfo.users[index];
        return  corpUser.corp_user.comment != undefined ? corpUser.corp_user.comment.replace(/ /g,'') : '';
    }

    $scope.isCommentVisible = function(index) {
        var corpUser = $scope.corporateInfo.users[index];
        if (corpUser.corp_user.allocation == undefined || corpUser.corp_user.allocation == 0 || corpUser.corp_user.allocation == null) {
            return false;
        }
        var comment = $scope.getComment(index);
        if (comment == '') {
            corpUser.corp_user.isCommentVisible = null;
        }
        if (corpUser.corp_user.isCommentVisible == undefined || corpUser.corp_user.isCommentVisible == null) {
            corpUser.corp_user.isCommentVisible = $scope.canExceedAllocationLimit(index);
        }
        if (index == 0) {
            console.log('isCommentVisible', corpUser.corp_user.isCommentVisible);
        }

        return corpUser.corp_user.isCommentVisible;
    }

    $scope.canExceedAllocationLimit = function(index) {
        var expenseType = $scope.getExpenseType(index);
        var corpUser = $scope.corporateInfo.users[index];

        if (corpUser.corp_user.allocation == undefined || corpUser.corp_user.allocation == null || corpUser.corp_user.allocation == '') {
            return false;
        }

        if (expenseType.limit_type == 'Soft') {
            return parseFloat(expenseType.limit_per_order) >= parseFloat(corpUser.corp_user.allocation) && parseFloat(expenseType.soft_limit_max) < parseFloat(corpUser.corp_user.allocation);
        }
        return false;
    }

    $scope.toBeAllocated = function() {
        return orderService.getTotal() - $scope.totalAllocated();
    }

    $scope.orderTotal = function() {
        return orderService.getTotal();
    }

    $scope.totalAllocated = function() {
        var result = 0;
        if ($scope.corporateInfo == null) {
            return 0;
        }
        for(var i = 0; i < $scope.corporateInfo.users.length; i++){
            if ($scope.corporateInfo.users[i].corp_user.allocation != undefined && $scope.corporateInfo.users[i].corp_user.allocation != null && $scope.corporateInfo.users[i].corp_user.allocation != '') {
                result += parseFloat($scope.corporateInfo.users[i].corp_user.allocation);
            }
        }
        return result;
    };

    $scope.initExpenseTypeSelect = function() {
        $('.exptype_select').dineinSelect({
            timeInterval: 10,
            frameHeight: 140,
            onlyOne: true
        });
    };

    $scope.initCodeSelect = function() {
        $('.code_select').dineinSelect({
            timeInterval: 10,
            frameHeight: 140,
            onlyOne: true
        });
    };

    //$scope.showPanel('deliveryAddress');
    $scope.showPanel('payment');

    $timeout(function(){
        closeAddressMenu();
    },0,false);

    $scope.openAddressMenu = function($event){
        $event.stopImmediatePropagation();
        if($($element).find('.registration_select.js-delivery-menu').hasClass('opened')){
            closeAddressMenu();
        }else{
            $($element).find('.registration_select.js-delivery-menu ul').slideDown(500);
            $($element).find('.registration_select.js-delivery-menu ').addClass('opened');
        }
    };
    function closeAddressMenu(){
        $($element).find('.registration_select.js-delivery-menu ul').slideUp(500);
        $($element).find('.registration_select.js-delivery-menu ').removeClass('opened');
    }
	$scope.initUserSettingsMobile = function (){
        $($element).find('.user-settings-mobile, .add-user-mobile').dineinSelect({
            timeInterval: 10,
            frameHeight: 140
        });
        //$($element).find('').dineinSelect({
        //    timeInterval: 10,
        //    frameHeight: 140
        //});
    };
});
