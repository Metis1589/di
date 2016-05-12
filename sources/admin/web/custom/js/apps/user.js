var userId = location.search.split('id=')[1];

dineinApp.controller('userAddressesController', function ($scope, $http, $filter) {
    $scope.userFormIsSubmitting = false;
    $scope.userSubmitError = '';
    $scope.editedUser = {};

    $http.get('/user/get-user-addresses', {params: {id: userId}}).success(function (response) {
        $scope.users = response;
    });

    $scope.filterUsers = function (user) {
        return user.record_type !== 'Deleted';
    };

    $scope.add = function(type) {
        initilizeEditedUser();
        showPopup();
    };

    $scope.edit = function(id) {
        $scope.editedUser = angular.copy($filter('filter')($scope.users, {id: id})[0]);
        console.log('edit');
        showPopup();
    };

    $scope.save = function () {
        $scope.userFormIsSubmitting = true;
        $http.post('/user/address-save', {
            user: $scope.editedUser
        }).success(function (response) {
            $scope.tableform.$setPristine(true);
            if (response.errors != undefined) {
                $scope.userSubmitError = response.errors[0].join();;
            } else {
                $scope.userSubmitError = '';
                var index = getIndexById(response.address_id);
                if (index > -1) {
                    $scope.users[index] = response;
                } else {
                    $scope.users.push(response);
                }
                initilizeEditedUser();
                closePopup();
            }
            $scope.userFormIsSubmitting = false;


        }).error(function (data) {
            $scope.userFormIsSubmitting = false;
            $scope.userSubmitError = data;
        });
    };
    
    $scope.hasUserSubmitError = function() {
        return $scope.userSubmitError != '';
    };

    $scope.setStatus = function(id, record_type) {
        $scope.editedUser = $filter('filter')($scope.users, {id: id})[0];
        $scope.editedUser.record_type = record_type;
        $scope.save();
    };

    var showPopup = function() {
        $scope.userSubmitError = '';
        $scope.tableform.$setPristine(true);
        $('#user-popup-open').click();
    };

    var initilizeEditedUser = function() {
        $scope.editedUser = {
            address_id: 0,
            user_id: userId,
        };
    };

    var closePopup = function() {
        $('.modal').click();
    };

    var getIndexById = function(id){
        return $scope.users.map(function(x) {return x.address_id; }).indexOf(id);
    };

});