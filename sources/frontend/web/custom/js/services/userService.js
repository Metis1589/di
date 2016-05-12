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
    //var _isLoggedIn = false;

    /**
     * Is user logged in
     * @returns {*}
     */
    this.isLoggedIn = function() {
        //return _isLoggedIn;
        console.log(docCookies.getItem('logged_in'));
        return docCookies.getItem('logged_in') === "true";
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
     * @returns {*}
     * @param is_remember
     */
    this.login = function(username, password, is_remember) {

        var deferred = apiService.getDeferred();

        apiService.post('login',
            {
                username: username,
                password: password,
                remember_me: is_remember
            })
            .success(function (data) {

                //_isLoggedIn = true;

                docCookies.setItem('logged_in', true, is_remember ? 3600 * 24 * 30 : null, '/');
                docCookies.setItem('username', data.name, is_remember ? 3600 * 24 * 30 : null, '/');
                docCookies.setItem('email', data.email, is_remember ? 3600 * 24 * 30 : null, '/');
                //if (isRemember) {
                //    docCookies.setItem('token', data.token, Infinity, '/');
                //}
                deferred.resolve(data.token);

                $rootScope.$broadcast('LOGGED_IN');
            })
            .error(function (status_code, error_message) {

                 docCookies.removeItem('logged_in','/');
                 docCookies.removeItem('username', '/');
                 //docCookies.removeItem('token', '/');
                 docCookies.removeItem('email','/');

                 deferred.reject({status_code: status_code, error_message: error_message});
            });

        return deferred.promise;
    };

    /**
     * request to reset password
     * @returns {*}
     */
    this.requestPasswordReset = function(username) {
        return apiService.post('request-password-reset', {
            username: username
        });
    };

    /**
     * reset password
     * @returns {*}
     */
    this.passwordReset = function(token, password) {
        return apiService.post('password-reset', {
            token: decodeURIComponent(token),
            password: password
        });
    };

    /**
     * activate
     * @returns {*}
     */
    this.activateAccount = function(token, password) {
        return apiService.post('activate-account', {
            token: decodeURIComponent(token)
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
    this.register = function(title, first_name, last_name, address1, address2, city, postcode, phone, username, password) {
        return apiService.post('register', {
            title        : title,
            first_name   : first_name,
            last_name    : last_name,
            address1     : address1,
            address2     : address2,
            city         : city,
            postcode     : postcode,
            phone        : phone,
            username     : username,
            password     : password
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
            last_name : profile.last_name,
            email : profile.email,
            password : profile.password
        }).success(function (data) {
            docCookies.setItem('username', data.name, null, '/');
            docCookies.setItem('email', data.email, null, '/');
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
        return apiService.post('save-user-address', {
            id: address_id,
            address: address
        });
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
     * get reviews
     * @returns {*}
     */
    this.getReviews = function() {
        return apiService.post('get-reviews-by-user', {});
    };

    /**
     * get corporate users
     * @returns {*}
     */
    this.getCorporateUsers = function(expense_type_id) {
        return apiService.get('corp-get-users', {
            expense_type_id: expense_type_id
        });
    };

    /**
     * add corporate user to the order
     * @returns {*}
     */
    this.addCorporateUser = function(index, first_name, last_name, email, company) {
        return apiService.post('corp-set-user', {
            index : index,
            first_name: first_name,
            last_name: last_name,
            email: email,
            company: company
        });
    };

    /**
     * remove corporate user to the order
     * @returns {*}
     */
    this.removeCorporateUser = function(index) {
        return apiService.post('corp-remove-user', {
            index : index
        });
    };

    this.setCorporateUserData = function(index, code_id, allocation, comment) {
        return apiService.post('corp-set-user-data', {
            index : index,
            code_id: code_id,
            allocation: allocation,
            comment: comment
        });
    }

    /**
     * user logout
     */
    this.logout = function() {
        apiService.post('logout');

        //_isLoggedIn = false;

        docCookies.removeItem('logged_in','/');

        docCookies.removeItem('username', '/');
        docCookies.removeItem('token', '/');
        docCookies.removeItem('email','/');

        $rootScope.$broadcast('LOGGED_OUT');
    };

    /**
     * Set last used address ID
     * @param name
     */
    this.setLastUsedAddress = function(id){
        docCookies.setItem('last_address',id, Infinity, '/');
    };

    /**
     * Get last used address ID
     */
    this.getLastUsedAddress = function(){
        return docCookies.getItem('last_address');
    };

    /**
     * Get stored user email
     * @returns {*}
     */
    this.getEmail = function (){
        return docCookies.getItem('email');
    }
});
