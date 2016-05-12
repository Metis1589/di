/**
 * Create the dineinApp module
 */
var dineinApp = angular.module('dineinApp', ['ngProgress', 'angular.filter']);
//dineinApp.filter('filterSelected', function() {
//    return function(items, field) {
//        var result = {};
//        angular.forEach(items, function(value, key) {
//            if (!value.hasOwnProperty(field)) {
//                result[key] = value;
//            }
//        });
//        return result;
//    };
//});

//dineinApp.run(function($httpBackend) {
//
//    $httpBackend.whenGET(/.*/).passThrough();
//    $httpBackend.whenPOST(/.*/).passThrough();
//});

dineinApp.value('isMobile', window.innerWidth < 1100);
dineinApp.directive('ngEnter', function() {
    return function(scope, element, attrs) {
        element.bind("keydown keypress", function(event) {
            if(event.which === 13) {
                scope.$apply(function(){
                    scope.$eval(attrs.ngEnter, {'event': event});
                });

                event.preventDefault();
                event.stopPropagation();
            }
        });
    };
});


dineinApp.controller('searchFromSinglePageRestaurantsController', function($scope, $window, $http, restaurantService, $timeout) {
    $scope.init = function () {
        $scope.postcode      = restaurantService.getPostcode();
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
    };
    $scope.init();
    $scope.selectedType = function(typeData){
        $scope.delivery_type = typeData.deliveryType;
        if('deliveryDate' in typeData){
            $scope.delivery_date = typeData.deliveryDate
        }
        if('deliveryTime' in typeData){
            $scope.delivery_time = typeData.deliveryTime;
        }
    };
    $scope.enterHandler = function($event){
        if($event.keyCode === 13){
            $scope.findRestaurants();
        }
    };
    $scope.findRestaurants = function() {
        $scope.form.postcode.$setDirty();
        $scope.form.postcode.$validate();
        if ($scope.form.$valid) {
            restaurantService.setPostcode($scope.postcode);
            restaurantService.setDeliveryType($scope.delivery_type);
            restaurantService.setDeliveryDate($scope.delivery_date);
            restaurantService.setDeliveryTime($scope.delivery_time);
            $window.location.href = $scope.searchUrl;
        }
    };
    $scope.recalculateCharge = function() {
        $scope.form.postcode.$setDirty();
        $scope.form.postcode.$validate();
        if ($scope.form.$valid) {
            restaurantService.setPostcode($scope.postcode);
            restaurantService.setDeliveryType($scope.delivery_type);
            restaurantService.setDeliveryDate($scope.delivery_date);
            restaurantService.setDeliveryTime($scope.delivery_time);

            restaurantService.calculateDeliveryCharge(
                $scope.postcode,
                $('#restaurant_id').val(),
                $scope.delivery_type,
                $scope.delivery_time
            );
            $.magnificPopup.close();
        }
    };
    $scope.findLocationError = '';
    $scope.findLocation = function() {
        $window.navigator.geolocation.getCurrentPosition(successHandler, errorHandler);
    };
    function successHandler(location){
        $http.get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' + location.coords.latitude + ',' + location.coords.longitude + '&sensor=true')
            .success(function (data, status, headers, config) {
                for (var i = 0; i < data.results.length; i++) {
                    for (var j = 0; j < data.results[i].address_components.length; j++) {
                        if (data.results[i].address_components[j].types[0] == 'postal_code') {
                            //console.log(data.results[i].address_components[j].short_name);
                            $scope.postcode = data.results[i].address_components[j].short_name;
                            $timeout(function(){
                                $scope.findRestaurants();
                            },0);
                        }
                    }
                }
            })
            .error(function (data, status, headers, config) {
                 $timeout(function() {
                     $scope.findLocationError = $scope.findErrorMessage;
                 },0);
            });

    }

    function errorHandler(error) {
        $timeout(function() {
            $scope.findLocationError = $scope.findErrorMessage;
        },0);
        console.log('Attempt to get location failed: ' + error.message);
    }
});

dineinApp.controller('homeController', function($scope, $window, $http, userService,restaurantService) {
    $scope.loggedin = userService.isLoggedIn();
    $scope.username = userService.getUsername();
    function getPostcode (){
        if($scope.loggedin && !restaurantService.getPostcode()){
            userService.getAddresses()
                 .success(function(data){
                     if(data && data.length > 0){
                         for(var i in data){
                             if('postcode' in data[i] && data[i].postcode){
                                 restaurantService.setPostcode(data[i].postcode);
                                 break;
                             }
                         }
                     }
                 });
        }
    }
    getPostcode();


    $scope.$on('LOGGED_IN',function(){
        $scope.loggedin = userService.isLoggedIn();
        $scope.username = userService.getUsername();
        getPostcode();
    });
});

dineinApp.controller('searchRestaurantsController', function($scope, $location, $filter, restaurantService, ngProgress) {
    if(!restaurantService.isValidDeliveryType()){
        debugger;
        restaurantService.setDeliveryTime(null);
        restaurantService.setDeliveryDate(null);
        restaurantService.setDeliveryType(null);
    }
    $scope.postcode = restaurantService.getPostcode();
    $scope.delivery_type = restaurantService.getDeliveryType();
    $scope.delivery_date = restaurantService.getDeliveryDate();
    $scope.delivery_time = restaurantService.getDeliveryTime();
    $scope.seo_area_id = $('#seo_area_id').val();
    $scope.cuisine_id = $('#cuisine_id').val();

    $scope.orderByField = 'eta';
    $scope.reverseSort = false;

    $scope.formatPath = function(path) {
        if (path == null) {
            return '';
        }

        //console.log(path, path.replace(/ /g, '-'), path.replace(' ', '-').replace(' ', '-'));

        return encodeURIComponent(path.replace(/ /g, '').replace(/\//g, '-')).toLowerCase();
    };

    $scope.changePostcode = function() {
        if ($scope.postcode && !$scope.postcodeForm.$invalid) {
            $scope.search();
        }
    };

    $scope.setType = function(newType){
        $scope.delivery_type = newType.deliveryType;
        if('deliveryDate' in newType){
            $scope.delivery_date = newType.deliveryDate
        }
        if('deliveryTime' in newType){
            $scope.delivery_time = newType.deliveryTime;
        }
        $scope.search();
    };
    $scope.$on('DELIVERY_TYPE_CHANGED',function(){
        $scope.delivery_type = restaurantService.getDeliveryType();
        $scope.delivery_date = restaurantService.getDeliveryDate();
        $scope.delivery_time = restaurantService.getDeliveryTime();
        $scope.search();
    });
    $scope.controllerClicked = function($event){
        $event.stopPropagation();
        $scope.$broadcast('searchControllerClicked');
    };
    $scope.filterRestaurants = function (restaurant) {

        var cuisine_result = false;
        var selected_cuisines_count = 0;

        angular.forEach($scope.filter.cuisines, function(filter_cuisine) {

            if (filter_cuisine.selected) {

                selected_cuisines_count++;

                angular.forEach(restaurant.cuisines, function (cuisine) {
                    if (filter_cuisine.id == cuisine.id) {
                        cuisine_result = true;
                    }
                });
            }
        });

        if (selected_cuisines_count == 0) {
            cuisine_result = true;
        }

        var price_range_result = false;
        var selected_price_ranges_count = 0;

        angular.forEach($scope.filter.price_ranges, function(filter_price_range) {

            if (filter_price_range.selected) {

                //console.log(filter_price_range.name, restaurant.price_range);

                selected_price_ranges_count++;

                if (restaurant.price_range == filter_price_range.value + 1) {

                    price_range_result = true;
                }
            }
        });

        if (selected_price_ranges_count == 0) {
            price_range_result = true;
        }

        var rating_result = false;
        var selected_ratings_count = 0;

        angular.forEach($scope.filter.ratings, function(filter_rating) {

            if (filter_rating.selected) {

                //console.log(filter_rating.name, restaurant.rating);

                selected_ratings_count++;

                if (restaurant.rating == filter_rating.value) {

                    rating_result = true;
                }
            }
        });

        if (selected_ratings_count == 0) {
            rating_result = true;
        }

        return cuisine_result && price_range_result && rating_result;
    };

    $scope.filterCharges = function (restaurant) {

        var result = false;
        var selected_count = 0;

        angular.forEach($scope.filter.charges, function(filter_charge) {

            if (filter_charge.selected) {

                selected_count++;
                if (filter_charge.from == 0) {
                    if (restaurant.delivery_charge <= filter_charge.to) {
                        result = true;
                    }
                } else if (restaurant.delivery_charge > filter_charge.from && restaurant.delivery_charge <= filter_charge.to) {
                    result = true;
                }
            }
        });

        if (selected_count == 0) {
            result = true;
        }

        return result;
    };

    $scope.filterETA = function (restaurant) {

        var result = false;
        var selected_count = 0;

        angular.forEach($scope.filter.etas, function(filter_eta) {

            if (filter_eta.selected) {

                selected_count++;

                if (restaurant.eta > filter_eta.from && restaurant.eta <= filter_eta.to) {

                    result = true;
                }
            }
        });

        if (selected_count == 0) {
            result = true;
        }

        return result;
    };

    $scope.filterDeliveryType = function (restaurant) {

        var result = false;
        var selected_count = 0;

        angular.forEach($scope.filter.delivery_types, function(delivery_type) {

            if (delivery_type.selected) {
                selected_count++;

                if ((restaurant.has_delivery && delivery_type.has_delivery) || (restaurant.has_collection && delivery_type.has_collection)) {
                    result = true;
                }
            }
        });

        if (selected_count == 0) {
            result = true;
        }

        return result;
    };

    $scope.showHideFilter = function() {
        $scope.isFilterShowed = !$scope.isFilterShowed;
    };

    $scope.search = function() {
        restaurantService.setPostcode($scope.postcode);
        restaurantService.setDeliveryType($scope.delivery_type);
        if(['CollectionAsap','DeliveryAsap'].indexOf($scope.delivery_type) > -1){
            restaurantService.setDeliveryTime(null);
            restaurantService.setDeliveryDate(null);
        }else{
            restaurantService.setDeliveryTime($scope.delivery_time);
            restaurantService.setDeliveryDate($scope.delivery_date);
        }
        ngProgress.start();

        restaurantService.search($scope.seo_area_id, $scope.cuisine_id)
            .success(function (data) {
                ngProgress.complete();
                 var result = [];
                if(data && angular.isArray(data)){
                    result = data.map(function(r,i){
                        if(r.eta == null){
                            r.eta = 55555;
                        }
                        var deliver_charge = parseFloat(r.delivery_charge);
                        if(!isNaN(deliver_charge) && r.delivery_charge !== null){
                            r.delivery_charge = deliver_charge;
                        }
                        return r;
                    });
                }
                $scope.restaurants = result;
                setTimeout(function() { initRestaurantList(); }, 500);
            })
            .error(function (status_code, error_message) {
                ngProgress.complete();
                $scope.error = error_message;
            });
    };

    var copyFilter = function(from, to, clear, name) {
        for (var id in $scope.filter[name]) {
            if (clear) {
                to[name][id].selected = false;
                from[name][id].selected = false;
            }
            else {
                to[name][id].selected = from[name][id].selected;
            }
        }
    };

    var copyFilters = function(from, to, clear) {
        copyFilter(from, to, clear, 'cuisines');
        copyFilter(from, to, clear, 'etas');
        copyFilter(from, to, clear, 'price_ranges');
        copyFilter(from, to, clear, 'ratings');
        copyFilter(from, to, clear, 'charges');
        copyFilter(from, to, clear, 'delivery_types');
        initRestaurantList();
    };

    var copyFilterFromTemp = function() {
        copyFilters($scope.temp_filter, $scope.filter, false);
    };

    var copyFilterToTemp = function() {
        copyFilters($scope.filter, $scope.temp_filter, false);
    };

    $scope.showFilter = function() {
        copyFilterToTemp();
    };

    $scope.applyFilter = function() {
        copyFilterFromTemp();
        initRestaurantList();
    };

    $scope.clearFilter = function () {
        copyFilters($scope.filter, $scope.temp_filter, true);
    };

    $scope.cancelFilter = function () {
        copyFilterToTemp();
        initRestaurantList();
    };

    $scope.search();

    $scope.bindRestaurantsTable = function() {
        initRestaurantList();
    };
});

dineinApp.filter('range', function() {
    return function(input, total) {
        total = parseInt(total);
        for (var i = 0; i < total; i += 0.25) {
          input.push(i);
        }
        return input;
    };
});

dineinApp.controller('restaurantController', function($location, $rootScope, $scope, $location, restaurantService) {
    $scope.tab         = 'menu';
    $scope.view_plate  = false;
    $scope.postcode    = restaurantService.getPostcode();
    $scope.category_id = null;

    if ($location && $location.absUrl()) {
        restaurantService.setLastVisitedRestaurantUrl($location.absUrl());
    }

    $('.header_logo_set').addClass('only_desctop');
    var restaurant_id   = $('#restaurant_id').val()
      , restaurant_name = $('#restaurant_name').val();

    angular.element($('.title')).html(restaurant_name);

    $scope.$on('scanner-started', function(event, args) {
        $scope.view_plate = args.view_plate;
    });

    $scope.selected = function(item, category_id) {
        $scope.tab = item;
        if (category_id) {
            $scope.category_id = category_id;
        }
        $('.mask_popup').click();
    };

    $scope.$watch(
        function () {
            return restaurantService.getSavedMenus();
        },
        function (newValue, oldValue) {
            $scope.menus = newValue;
            console.log('$scope.menus', newValue);
        });

    restaurantService.getRestaurantReviews(restaurant_id)
        .success(function (data) {
            $scope.reviews = data.reviews;
            $scope.rating = data.rating;
        })
        .error(function (status_code, error_message) {
            $scope.error = error_message;
        });


    $scope.initializeMap = function(){
        $scope.selected('location');

        var lat = $('#lat').val();
        var long = $('#long').val();

        if (lat == '' || long == ''){
            return;
        }

        setTimeout(function(){
            map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: new google.maps.LatLng(lat, long),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        // current location marker
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, long),
            map: map,
            icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
        });
        }, 100);
    }
    //restaurantService.getMenus(restaurant_id)
    //    .success(function (data) {
    //        $scope.menus = data;
    //
    //        $scope.menu = data[0];
    //    })
    //    .error(function (status_code, error_message) {
    //        ngProgress.complete();
    //        $scope.error = error_message;
    //});

    //$scope.menu_selected = function(id) {
    //    $scope.tab = 'menu';
    //
    //    $scope.menu = angular.copy($filter('filter')($scope.menus, {id: id})[0]);
    //
    //    //close overlay
    //    $('.mask_popup').click();
    //
    //};

//    $scope.showPage = function(page) {
//        $scope.page = page;
//    };

    //$scope.showPage('menus');
});

dineinApp.controller('contactUsController', function($scope, userService, ngProgress) {
    $scope.contactUsError = '';
    $scope.is_mail_sent   = false;

    $scope.submit = function() {
        ngProgress.start();
        userService.contactUs($scope.first_name, $scope.last_name, $scope.username, $scope.phone, $scope.order_number, $scope.message)
            .success(function (data) {
                $scope.contactUsError = '';
                $scope.is_mail_sent   = true;
                ngProgress.complete();
            })
            .error(function (status_code, error_message) {
                $scope.contactUsError = error_message;
                ngProgress.complete();
            });
    };
});

dineinApp.controller('suggestController', function($scope, restaurantService, ngProgress) {
    $scope.suggestError = '';
    $scope.cuisines     = [];
    $scope.is_mail_sent = false;

    $scope.setCuisine = function(cuisine) {
        $scope.cuisine = cuisine;
    };

    $scope.setArea = function(area) {
        $scope.area = area;
    };

    $scope.suggest = function() {
        ngProgress.start();

        restaurantService.suggest($scope.name, $scope.cuisine, $scope.area, $scope.phone, $scope.postcode, $scope.email)
            .success(function(data) {
                $scope.suggestError = '';
                $scope.is_mail_sent = true;
                ngProgress.complete();
            })
            .error(function(status_code, error_message) {
                $scope.suggestError = error_message;
                ngProgress.complete();
            });
    };
});

dineinApp.controller('reviewController', function($scope, restaurantService, ngProgress) {
    $scope.reviewError  = '';
    $scope.is_submitted = false;

    $scope.suggest = function() {
        ngProgress.start();
        restaurantService.addReview($('#restaurant_id').val(), $scope.order_number, $scope.rating, $scope.review_title, $scope.text)
            .success(function(data) {
                $scope.reviewError = '';
                $scope.is_submitted = true;
                ngProgress.complete();
            })
            .error(function(status_code, error_message) {
                $scope.reviewError = error_message;
                ngProgress.complete();
            });
    };
});

dineinApp.controller('signUpRestaurantController', function($scope, restaurantService, ngProgress) {
    $scope.signUpRestoError = '';
    $scope.is_submitted     = false;
    $scope.interested       = false;
    $scope.is_mail_sent     = false;
    $scope.cuisine_1        = false;
    $scope.cuisine_2        = false;
    $scope.cuisine_3        = false;
    $scope.offer_delivery   = false;
    $scope.takeaway_service = false;
    $scope.takeaways_count  = '';

    $scope.register = function() {
        ngProgress.start();
        restaurantService.singUp(
            $scope.name,
            $scope.address1,
            $scope.address2,
            $scope.city,
            $scope.postcode,
            $scope.phone,
            $scope.cuisine_1,
            $scope.cuisine_2,
            $scope.cuisine_3,
            $scope.offer_delivery,
            $scope.takeaway_service,
            $scope.takeaways_count,
            $scope.first_name,
            $scope.last_name,
            $scope.role,
            $scope.email,
            $scope.contact_phone)
        .success(function(data) {
            $scope.signUpRestoError = '';
            $scope.is_submitted = true;
            $scope.is_mail_sent = true;
            ngProgress.complete();
        })
        .error(function(status_code, error_message) {
            $scope.signUpRestoError = error_message;
            ngProgress.complete();
        });
    };

    $scope.setCuisine1 = function(id) {
        $scope.cuisine_1 = id;
    };

    $scope.setCuisine2 = function(id) {
        $scope.cuisine_2 = id;
    };

    $scope.setCuisine3 = function(id) {
        $scope.cuisine_3 = id;
    };

    $scope.setOfferDelivery = function(val) {
        $scope.offer_delivery = val;
    };

    $scope.setTakeawayService = function(val) {
        $scope.takeaway_service = val;
    };
});

dineinApp.controller('paymentController', function($scope, $filter, orderService, $window) {
    $scope.setParams = function(params){
        orderService.savePayment(params).success(function (data) {
            if (data.result == 'CANCELLED' || data.result == 'REFUSED'){
                $window.location = '/order/checkout?payment_result=' + data.result;
            } else {
                $window.location = '/order/tracker?order_number=' + data.order_number +'&clearOrder=true';
            }
        })
        .error(function (status_code, error_message) {

        });
    };
 });

dineinApp.controller('userMenuController', function ($location,$window, $rootScope, $scope, userService,restaurantService) {

    $scope.loggedin = userService.isLoggedIn();

    $scope.$watch(userService.getUsername,
        function (newValue, oldValue) {
            $scope.username = newValue;
        });

    $scope.restaurantUrl = restaurantService.getLastVisitedRestaurantUrl();

    $rootScope.$on('LOGGED_IN', function () {
        $scope.loggedin = userService.isLoggedIn();
        $scope.username = userService.getUsername();
    });
    $rootScope.$on('LOGGED_OUT',function(){
        $scope.loggedin = false;
        $scope.username = null;
    });
    $scope.menuClick = function ($event) {
        if ($('#nav_list').hasClass('back-button')) {
            $rootScope.$broadcast('scanner-started', { view_plate: false });
            $('#nav_list').removeClass('back-button');
            $.magnificPopup.close();
        } else {
            $scope.$broadcast('userMenuTrigger',{event:$event});
        }
    };
    $scope.clickRightButton = function($event){
        if($scope.restaurantUrl){
            location.href = $scope.restaurantUrl;
            $scope.menuClick($event);
        }
    };
    $scope.logoutAction = function () {
        $rootScope.loggedin = $scope.loggedin = false;
        userService.logout();
        $window.location.href = "/";
    };
});

