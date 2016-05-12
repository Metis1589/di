'use strict';

dineinApp.service('apiService', function($http, $q) {

    /**
     * transformation for POST parameters
     * @param data
     * @returns {*}
     */
    function transform(data){
        return $.param(data);
    }

    /**
     * get deferred for api calls
     * @returns {*}
     */
    this.getDeferred = function() {
        var deferred = $q.defer();
        var promise = deferred.promise;

        promise.success = function(fn) {
            promise.then(fn);
            return promise;
        };

        promise.error = function(fn) {
            promise.then(null, function(response) {
                fn(response.status_code, response.error_message);
                console.error(response.status_code + '; ' + response.error_message);
            });
            return promise;
        };

        return deferred;
    };

    /**
     * post
     * @returns {*}
     */
    this.post = function(url, data) {

        if (typeof data === 'undefined') {
            data = {};
        }

        url = $('#_gateway_url').val() + url;

        var req = {
            method: 'POST',
            url: url,
            withCredentials: true,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            transformRequest: transform,
            data: data
        };

        var deferred = this.getDeferred();

        $http(req)
            .success(function (response) {
                if (response.status_code == 0) {
                    deferred.resolve(response.data);
                }
                else {
                    //console.log(response.status_code, response.error_message);
                    deferred.reject(response);
                }
            })
            .error(function (data, status, headers, config) {
                deferred.reject({status_code: 501, error_message: data + status + headers + config});
            });

        return deferred.promise;
    };

    /**
     * get
     * @returns {*}
     */
    this.get = function(url, params) {

        if (typeof params === 'undefined') {
            params = {};
        }

        url = $('#_gateway_url').val() + url;

        var req = {
            method: 'GET',
            url: url,
            withCredentials: true,
            //headers: {
            //    'Content-Type': 'application/x-www-form-urlencoded'
            //},
            //transformRequest: transform,
            params: params
        };

        var deferred = this.getDeferred();

        $http(req)
            .success(function (response) {
                if (response.status_code == 0) {
                    deferred.resolve(response.data);
                }
                else {
                    deferred.reject(response);
                }
            })
            .error(function (data, status, headers, config) {
                deferred.reject({status_code: 501, error_message: data + status + headers + config});
            });

        return deferred.promise;
    };
});

