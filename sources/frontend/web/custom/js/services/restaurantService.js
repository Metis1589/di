'use strict';

/**
 * Service performing restaurant related requests
 */
dineinApp.service('restaurantService', function($rootScope, apiService) {
    var _menus = null;
    /**
     * Get postcode
     * @returns {*}
     */
    this.getPostcode = function() {
        var result = docCookies.getItem('postcode');

        if (result == null) {
            return '';
        }

        return result;
    };

    /**
     * Set postcode
     * @param postcode
     */
    this.setPostcode = function(postcode) {
        docCookies.setItem('postcode', postcode, Infinity, '/');
    };

    /**
     * Get delivery type
     * @returns {*}
     */
    this.getDeliveryType = function() {
        var result = docCookies.getItem('delivery_type');

        if (result == null || result == 'null') {
            result = 'DeliveryAsap';
        }

        return result;
    };
    this.getDeliveryDate = function() {
        return docCookies.getItem('delivery_date');
    };
    this.getDeliveryTime = function() {
        return docCookies.getItem('delivery_time');
    };

    /**
     * Set delivery type
     * @param delivery_type
     */
    this.setDeliveryType = function(delivery_type) {
        if(delivery_type === null || angular.isUndefined(delivery_type)){
            docCookies.removeItem('delivery_type','/');
        }else {
            docCookies.setItem('delivery_type', delivery_type, Infinity, '/');
        }
    };

    this.setDeliveryDate = function(delivery_date) {
        if(delivery_date === null || angular.isUndefined(delivery_date)){
            docCookies.removeItem('delivery_date','/');
        }else{
            docCookies.setItem('delivery_date', delivery_date, Infinity, '/');
        }
    };

    this.setDeliveryTime = function(delivery_time) {
        if(delivery_time === null || angular.isUndefined(delivery_time)){
            docCookies.removeItem('delivery_time','/');
        }else{
            docCookies.setItem('delivery_time', delivery_time, Infinity, '/');
        }
    };

    /**
     *
     * @param deliveryDate
     */
    this.isValidDeliveryDate = function(deliveryDate){
        return (new Date()).getDate()-1 < (new Date(deliveryDate)).getDate();
    };

    /**
     * Validate delivery type
     */
    this.isValidDeliveryType = function(){
        if(this.getDeliveryType()
             && this.getDeliveryType().search('Later') > -1
             && !this.isValidDeliveryDate(this.getDeliveryDate())){
            return false;
        }else{
            return true;
        }
    };

    /**
     * Get restaurants list
     * @returns {*}
     */
    this.search = function(seo_area_id, cuisine_id) {
        var data = {
            postcode: this.getPostcode(),
            delivery_type: this.getDeliveryType(),
            seo_area_id: seo_area_id,
            cuisine_id: cuisine_id
        };
        if(docCookies.hasItem('delivery_date')){
            data['later_date'] =  this.getDeliveryDate();
            if(docCookies.hasItem('delivery_time')){
                data['later_time'] =  this.getDeliveryTime();
            }
        }
        return apiService.get('restaurants-search', data);
    };

    this.suggest = function(name, cuisine, area, phone, postcode, email) {
        return apiService.post('suggest-restaurant', {
            name    : name,
            cuisine : cuisine,
            area    : area,
            phone   : phone,
            postcode: postcode,
            email   : email
        });
   };
   
   this.singUp = function(restaurant_name, restaurant_address1, restaurant_address2, 
   restaurant_city, restaurant_postcode, restaurant_phone, cuisine_1, cuisine_2, cuisine_3, offer_delivery, takeaway_service, takeaways_count, first_name, last_name, role, email, contact_phone) {
        return apiService.post('signup-restaurant', {
            restaurant_name    : restaurant_name,
            restaurant_address1 : restaurant_address1,
            restaurant_address2    : restaurant_address2,
            restaurant_city   : restaurant_city,
            restaurant_postcode : restaurant_postcode,
            restaurant_phone   : restaurant_phone,
            cuisine_1 : cuisine_1,
            cuisine_2 : cuisine_2,
            cuisine_3 : cuisine_3,
            offer_delivery : offer_delivery,
            takeaway_service : takeaway_service,
            takeaways_count : takeaways_count,
            first_name : first_name,
            last_name : last_name,
            role : role,
            email : email,
            contact_phone : contact_phone
        });
   };

    this.addReview = function(restaurant_id, order_number, rating, title, text) {
        return apiService.post('add-review', {
            restaurant_id: restaurant_id,
            order_number: order_number,
            rating: rating,
            title: title,
            text: text
        });
    };

    /**
     * Get menu
     * @param restaurant_id
     * @returns {*}
     */
    this.getMenus = function (restaurant_id) {

        var deferred = apiService.getDeferred();

        //console.log(_menus);

        if (_menus !== null) {
            deferred.resolve(_menus);
        }
        else {
            apiService.get('get-menus', {
                restaurant_id: restaurant_id
            }).success(function (data) {
                console.log('get-menus', data);
                _menus = data;

                deferred.resolve(data);

            }).error(function (status_code, error_message) {
                deferred.reject({status_code: status_code, error_message: error_message});
            });
        }

        return deferred.promise;
    };

    this.getSavedMenus = function() {
        return _menus;
    };

    this.getRestaurantReviews = function(restaurant_id) {
         return apiService.get('get-reviews-by-restaurant', {
            restaurant_id: restaurant_id
        });
    };

    /**
     * Set url for last visited restaurant
     * @param url
     */
    this.setLastVisitedRestaurantUrl = function(url){
        docCookies.setItem('last_visited_restaurant', url, Infinity, '/');
    };

    /**
     * Get url for last visited restaurant
     * @returns {*}
     */
    this.getLastVisitedRestaurantUrl =  function(){
        return docCookies.getItem('last_visited_restaurant');
    };

    this.calculateDeliveryCharge = function(postcode, restaurant_id, delivery_type, delivery_date, delivery_time){

        var deferred = apiService.getDeferred();

        apiService.get('get-delivery-charge', {
            postcode: postcode,
            restaurant_id: restaurant_id,
            delivery_type:delivery_type || '',
            later_date:delivery_date || '',
            later_time:delivery_time || ''
        })
        .success(function (data) {
            deferred.resolve(data);

            $rootScope.$broadcast('DELIVERY_INFO_CHANGED');
        })
        .error(function (status_code, error_message) {
                deferred.reject({status_code: status_code, error_message: error_message});
        });

        return deferred.promise;
    };

    /**
     *
     * @param restaurant_id
     * @returns {*}
     */
    this.getEta  = function(restaurant_id){
        return apiService.get('get-delivery-time', {
            restaurant_id: restaurant_id
        });
    }
});
