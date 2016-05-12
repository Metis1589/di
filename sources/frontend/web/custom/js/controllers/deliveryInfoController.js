/**
 * Created by jarik on 5/26/15.
 */

dineinApp.controller('deliveryInfoController',function($q, $scope, $rootScope, restaurantService, userService, $element, orderService, $timeout){
    var restaurant_id = $('#restaurant_id').val();
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
    $scope.loggedin = userService.isLoggedIn();
    $scope.lastAddressId = userService.getLastUsedAddress();
    $scope.userAddresses = {};
    $scope.closeDelivTypeMethod = function(){};
    $scope.openTypeHandler = function(){
        slideUpAddresses();
    };
    $scope.addNewAddressOpened = false;
    $rootScope.$on('LOGGED_IN', function () {
        $scope.loggedin = userService.isLoggedIn();
        getAddresses().then(function(){
            $timeout(function(){
                //setAddressTitle(getPreviousAddress());
            },0,false);
        });
        $timeout(function(){
            $('.delivery_location').dineinSelect({
                timeInterval: 1,
                frameHeight: 200
            });

        },0,false);
    });


    $scope.$watch('loggedin',function(newVal){
        if(angular.isDefined(newVal) && newVal == true){
            $($element).on('dineinSelect.click','.delivery_location .pseudo_input.no-update',function(){
                if(angular.isFunction($scope.closeDelivTypeMethod)){
                    $scope.closeDelivTypeMethod();
                }
            });
            $timeout(function(){
                setAddressTitle(getPreviousAddress());
            },100,false);
        }
    });
    $scope.setDelivery = function(data){
        $scope.delivery_type = data.deliveryType;
        if('deliveryDate' in data){
            $scope.delivery_date = data.deliveryDate
        }
        if('deliveryTime' in data){
            $scope.delivery_time = data.deliveryTime;
        }
        restaurantService.calculateDeliveryCharge($scope.postcode,restaurant_id,$scope.delivery_type,$scope.delivery_date,$scope.delivery_time)
             .success(function(data){
                 if(data && 'delivery_charge' in data){
                     if(data.delivery_charge > 0){
                         restaurantService.setDeliveryType($scope.delivery_type);
                         restaurantService.setDeliveryDate($scope.delivery_date);
                         restaurantService.setDeliveryTime($scope.delivery_time);
                         $.magnificPopup.close();
                         orderService.setDeliveryCharge(data.delivery_charge);
                         $rootScope.$broadcast('DELIVERY_TYPE_CHANGED');
                         $rootScope.$broadcast('DELIVERY_CHARGE_UPDATED',{delivery_charge:data.delivery_charge});
                         $rootScope.$emit('POSTCODE_UPDATED',{postcode:data.postcode});
                     }else{
                         slideUpAddresses();
                     }
                 }
             })
             .error(function(data){
                 console.error('calculateDeliveryCharge:error');
                 console.dir(data);
                 $.magnificPopup.close();
             });
    };

    function setAddressTitle(addr){
        var title = '';
        if(addr){
            title = addr.name;
            $($element).find('.delivery_location .pseudo_input').text(title);
            $scope.lastAddressId = addr.id;
        }
    }
    function slideUpAddresses(){
        $($element).find('.delivery_location.opened').removeClass('opened');
        $($element).find('.delivery_location ul').slideUp(10);
    }
    $scope.selectAddress = function($event,addr){
        $event.stopPropagation();
        updateDeliveryCharge(addr.postcode).then(
             function(){
                 userService.setLastUsedAddress(addr.id);
                 setAddressTitle(addr);
                 $.magnificPopup.close();
             },
             function(){
                 alert('Can\'t deliver to that address');
                 setAddressTitle(getPreviousAddress());
             }
        );
    };

    $scope.clickMenu = function ($event){
        $event.stopPropagation();
        var menuItem = $($($element).find('.delivery_location')[0]);
        if(menuItem.hasClass('opened')){
            // close
            menuItem.removeClass('opened');
            $('ul',menuItem).slideUp(500);
        }else{
            // open
            menuItem.addClass('opened');
            $('ul',menuItem).slideDown(500);
        }
    };

    function getPreviousAddress(){
        var addr = false;
        if(userService.getLastUsedAddress() in $scope.userAddresses){
            addr = $scope.userAddresses[userService.getLastUsedAddress()];
        }
        if(addr === false){
            addr = $scope.userAddresses[Object.keys($scope.userAddresses)[0]];
        }
        return addr;
    }
    $scope.postcodeChanged = function($event){
        if(angular.isDefined($scope.postcode)){
            updateDeliveryCharge($scope.postcode)
                 .then(function(){},function(){
                     $scope.postcode = restaurantService.getPostcode();
                     $.magnificPopup.close();
                 });
        }

    };
    function updateDeliveryCharge(postcode){
        var deffer = $q.defer();
        restaurantService.calculateDeliveryCharge(postcode,restaurant_id,$scope.delivery_type,$scope.delivery_date,$scope.delivery_time)
             .success(function(data){
                 if(data && 'delivery_charge' in data){
                     if(data.delivery_charge !== null){
                         $scope.postcode = postcode;
                         restaurantService.setPostcode(postcode);
                         slideUpAddresses();
                         orderService.setDeliveryCharge(data.delivery_charge);
                         $rootScope.$broadcast('DELIVERY_CHARGE_UPDATED',{delivery_charge:data.delivery_charge});
                         $rootScope.$emit('POSTCODE_UPDATED',{postcode:data.postcode});
                         deffer.resolve();
                     }else{

                         deffer.reject();
                         slideUpAddresses();
                     }
                 }
             })
             .error(function(data){
                 console.error('calculateDeliveryCharge:error');
                 console.dir(data);
                 $.magnificPopup.close();
             });
        return deffer.promise;
    }
    $scope.mainVisible = true;
    // Isolate from parent scope
    function getAddresses(){
        var d = $q.defer();
        userService.getAddresses()
             .success(function(data){
                 var addresses = {};
                 var emptyNameCounter = 1;
                 data.map(function(a){
                     addresses[a.id] = a;
                     if(a.name === null){
                         a.name = 'ADDRESS LINE '+emptyNameCounter;
                         emptyNameCounter++;
                     }
                 });
                 $scope.userAddresses = addresses;
                 d.resolve();
             })
             .error(function(){
                 $scope.userAddresses = {};
                 d.reject();
             });
        return d.promise;
    }

    $scope.loggedin && getAddresses().then(function(){
        setAddressTitle(getPreviousAddress());
    });

    $scope.onAddNewAddress = function($event){
        $scope.mainVisible = false;
        $scope.addNewAddressOpened = true;
        $scope.resetAddressForm();
        slideUpAddresses();
    };
    $scope.saveNewAddressError = '';
    $scope.saveNewAddress = function($event){
        if($scope.newAddressForm.$valid){
            userService.saveAddress('',{
                name: $scope.name,
                title: $scope.title,
                city: $scope.city,
                phone: $scope.phone,
                postcode: $scope.postcode,
                first_name: $scope.firstName,
                last_name: $scope.lastName,
                address1: $scope.address1,
                address2: $scope.address2,
                email: $scope.email,
                instructions: $scope.instructions
            }).success(function(data){
                var addresses = {};
                var emptyAddressCounter = 1;
                data.forEach(function(a){
                    if(a.name === null){
                        a.name = 'ADDRESS LINE '+emptyAddressCounter;
                        emptyAddressCounter++;
                    }
                    addresses[a.id] = a;
                });
                $scope.userAddresses = addresses;
                $scope.cancel();
                $scope.addNewAddressOpened = false;
                $rootScope.$broadcast('CREATED_NEW_USER_ADDRESS',addresses);
            }).error(function (status_code, error_message) {
                $scope.saveNewAddressError = error_message;
            });
        }
    };
    $scope.cancel = function($event){
        $scope.resetAddressForm();
        $scope.mainVisible = true;
    };

    $scope.resetAddressForm = function() {
        // Reset
        $scope.address2 = $scope.address1 = $scope.lastName
            = $scope.firstName = $scope.postcode
            = $scope.phone = $scope.city = $scope.title = $scope.name = '';
        $scope.newAddressForm.$setPristine();
    }

    // Delivery info on restaurant view on mobile device
    $scope.newSelectedAddress = false;
    $scope.selectAddressWOUpdate = function($event,addr){
        $event.stopPropagation();
        $scope.newSelectedAddress = addr;
        slideUpAddresses();
    };
    $scope.eventDelivery = false;
    $scope.$on('NEW_DELIVERY_TYPE_SELECTED',function(event,arg){
        $scope.eventDelivery = arg;
    });
    $scope.saveNewData = function(){
        var postcode = false;
        // User not logged
        if($scope.loggedin === false && angular.isDefined($scope.postcode)){
            postcode = $scope.postcode;
        }else if ($scope.newSelectedAddress !== false){
            // User logged in and selected new address
            postcode = $scope.newSelectedAddress.postcode;
        }else if($scope.loggedin === true && angular.isDefined($scope.postcode)){
            postcode = $scope.postcode;
        }

        if(postcode !== false){
            updateDeliveryCharge(postcode).then(
                 function(){
                     if($scope.loggedin === false && angular.isDefined($scope.postcode)){
                         $scope.postcode = restaurantService.getPostcode();
                     }else if ($scope.newSelectedAddress !== false){
                         userService.setLastUsedAddress($scope.newSelectedAddress.id);
                         setAddressTitle($scope.newSelectedAddress);
                     }

                     $.magnificPopup.close();
                     // User select new address and new delivery type
                     if($scope.eventDelivery !== false){
                         $scope.setDelivery($scope.eventDelivery);
                     }
                 },
                 function(){
                     alert('Can\'t deliver to that address');
                     setAddressTitle(getPreviousAddress());
                 }
            );
        }else{
            // User do not select new address
            // Select only new delivery type
            if($scope.eventDelivery !== false){
                $scope.setDelivery($scope.eventDelivery);
            }
        }
    };
    $scope.resetNewAddressForm = function (){
        $scope.name ='';
        $scope.title  ='';
        $scope.city  ='';
        $scope.phone  ='';
        $scope.firstName ='';
        $scope.lastName ='';
        $scope.address1 ='';
        $scope.address2 ='';
        $scope.email ='';
        $scope.instructions ='';
    };
    $scope.cancelPopup = function(){
        $scope.delivery_type = null;
        $timeout(function(){
            $scope.delivery_type = restaurantService.getDeliveryType();
            if($scope.delivery_type == 'undefined' || $scope.delivery_type == undefined || $scope.delivery_type== 'null'){
                $scope.delivery_type = null;
            }
            $scope.delivery_date = restaurantService.getDeliveryDate();
            $scope.delivery_time = restaurantService.getDeliveryTime();
            $scope.postcode = restaurantService.getPostcode();
            $scope.resetNewAddressForm();
        },250,true);
    };
});