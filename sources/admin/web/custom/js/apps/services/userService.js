'use strict';

/**
 * Service performing user related requests and calculations
 */
dineinApp.service('userService', function($http, $q, $rootScope, apiService) {

    /**
     * is logged in
     * @type {boolean}
     * @private
     */
    var _isLoggedIn = false;

    /**
     * Is user logged in
     * @returns {*}
     */
    this.isLoggedIn = function() {
        return _isLoggedIn;
    };

    /**
     * username
     * @returns {*}
     */
    this.getUsername = function() {
        return docCookies.getItem('username');
    };

    /**
     * token
     * @returns {*}
     */
    this.getToken = function() {
        return docCookies.getItem('token');
    };

    /**
     * Perform Login
     * @param username
     * @param password
     * @param isRemember
     * @returns {*}
     */
    this.login = function(username, password, isRemember) {

        var deferred = apiService.getDeferred();

        apiService.post('login',
            {
                username: username,
                password: password
            })
            .success(function (data) {

                _isLoggedIn = true;

                if (isRemember) {
                    docCookies.setItem('username', username, Infinity, '/');
                    docCookies.setItem('token', data, Infinity, '/');
                }

                deferred.resolve(data);
            })
            .error(function (status_code, error_message) {
                _isLoggedIn = false;

                docCookies.removeItem('username');
                docCookies.removeItem('token');

                deferred.reject({status_code: status_code, error_message: error_message});
            });

        return deferred.promise;
    };
    
    /**
     * Perform Login
     * @param username
     * @param password
     * @param isRemember
     * @returns {*}
     */
    this.internalLogin = function(username, password) {

        var deferred = apiService.getDeferred();

        apiService.post('internal-login',
            {
                username: username,
                password: password
            })
            .success(function (data) {

                _isLoggedIn = true;

                deferred.resolve(data);
            })
            .error(function (status_code, error_message) {
                _isLoggedIn = false;

                deferred.reject({status_code: status_code, error_message: error_message});
            });

        return deferred.promise;
    };

    /**
     * reset password
     * @returns {*}
     */
    this.passwordReset = function(username) {
        return apiService.post('password-reset', {
            username: username
        });
    };

    /**
     * Register user account
     * @param title
     * @param first_name
     * @param last_name
     * @param address1
     * @param address2
     * @param city
     * @param postcode
     * @param mobile_number
     * @param phone_number
     * @param email
     * @param username
     * @param password
     * @returns {*}
     */
    this.register = function(title, first_name, last_name, address1, address2, city, postcode, mobile_number, phone_number, email, username, password) {
        return apiService.post('register', {
            title: title,
            first_name: first_name,
            last_name: last_name,
            address1: address1,
            address2: address2,
            city: city,
            postcode: postcode,
            mobile_number: mobile_number,
            phone_number: phone_number,
            email: email,
            username: username,
            password: password
        });
    };

    /**
     * get user profile
     * @returns {*}
     */
    this.getProfile = function() {
        return apiService.get('get-user-profile');
    };

    /**
     * save user profile
     * @param profile
     * @returns {*}
     */
    this.setProfile = function(profile) {
        return apiService.post('set-user-profile', {
            first_name: profile.first_name,
            last_name: profile.last_name
        });

    };

    /**
     * get previous orders
     * @returns {*}
     */
    this.getOrders = function() {
        return apiService.get('get-user-orders');
    };

    /**
     * get addresses
     * @returns {*}
     */
    this.getAddresses = function() {
        return apiService.get('get-user-addresses');
    };

    /**
     * save address
     * @param address_id
     * @param address
     */
    this.saveAddress = function(address_id, address) {

    };

    /**
     * Send contact us request
     * @param first_name
     * @param last_name
     * @param email
     * @param phone
     * @param order_number
     * @param message
     * @returns {*}
     */
    this.contactUs = function(first_name, last_name, email, phone, order_number, message) {
        return apiService.post('contact-us', {
            first_name: first_name,
            last_name: last_name,
            email: email,
            phone: phone,
            order_number: order_number,
            message: message
        });
    };

    /**
     * suggest restaurant
     * @param restaurant_name
     * @param cuisine_id
     * @param area_id
     * @param restaurant_phone_number
     * @param restaurant_postcode
     * @param email
     * @returns {*}
     */
    this.suggestRestaurant = function(restaurant_name, cuisine_id, area_id, restaurant_phone_number, restaurant_postcode, email) {
        return apiService.post('suggest-restaurant', {
            restaurant_name: restaurant_name,
            cuisine_id: cuisine_id,
            area_id: area_id,
            restaurant_phone_number: restaurant_phone_number,
            restaurant_postcode: restaurant_postcode,
            email: email
        });
    };

    /**
     * add restaurant review
     * @param restaurant_id
     * @param order_number
     * @param rating
     * @param title
     * @param review
     */
    this.addReview = function(restaurant_id, order_number, rating, title, review) {
        return apiService.post('add-review', {
            restaurant_id: restaurant_id,
            order_number: order_number,
            rating: rating,
            title: title,
            review: review
        });
    };

    /**
     * user logout
     */
    this.logout = function() {
        apiService.post('logout');

        _isLoggedIn = false;

        docCookies.removeItem('username', '/');
        docCookies.removeItem('token', '/');

        $rootScope.$broadcast('LOGGED_OUT');
    }
});
