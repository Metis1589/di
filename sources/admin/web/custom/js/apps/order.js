var order_id = location.search.split('id=')[1];

dineinApp.directive('elastic', [
    '$timeout',
    function($timeout) {
      return {
        restrict: 'A',
        link: function($scope, element) {
          var resize = function() {
            return element[0].style.height = "" + element[0].scrollHeight + "px";
          };
          element.on("blur keyup change", resize);
          $timeout(resize, 0);
        }
      };
    }
  ]);

dineinApp.directive('ngConfirmClick', [ function () {
    return {
         priority: -1,
         restrict: 'A',
         require: 'ngModel',
         link: function (scope, element, attrs, modelCtrl) {
                   var message = attrs.ngConfirmClick;

                    modelCtrl.$parsers.push(function (inputValue) {
                    var modelValue = modelCtrl.$modelValue;

                    if (inputValue !== modelValue && message && !confirm(message)) {
                        modelCtrl.$setViewValue(modelValue);
                        modelCtrl.$render();
                    }

                    return modelCtrl.$viewValue;
                });
          }
    }
}]);


dineinApp.controller('orderController', function ($scope, $window, userService, apiService, $filter) {
    $scope.isChanged   = true;
    $scope.editedOrder = {};
    $scope.selectedOrderStatus = {};

    $scope.filterOrders = function (order) {
        return order.record_type !== 'Deleted';
    };

    $scope.timerRunning = true;

    $scope.startTimer = function (){
        $scope.$broadcast('timer-start');
        $scope.timerRunning = true;
    };

    $scope.stopTimer = function (){
        $scope.$broadcast('timer-stop');
        $scope.timerRunning = false;
    };

    var client_key = $('#client_key').val()
      , api_token  = $('#api_token').val();

    $scope.valid = function(index, order){
        console.log($scope.getTotal(order));
        if ($($('#order-table tr')[index + 1]).find('.ng-invalid').length > 0 || $scope.getTotal(order)) {
            $($('#order-table tr')[index + 1]).find('.ng-invalid').addClass('ng-touched ng-dirty');
            return false;
        }
        return true;
    };

    $scope.getTotal = function(order){
        if (order.client_refund == null){
            order.client_refund = 0;
        }

        if (order.restaurant_refund == null){
            order.restaurant_refund = 0;
        }

        if (order.client_refund_diff == null){
            order.client_refund_diff = 0;
        }

        if (order.restaurant_refund_diff == null){
            order.restaurant_refund_diff = 0;
        }

        //console.log(parseFloat(order.client_refund) + parseFloat(order.restaurant_refund) + parseFloat(order.client_refund_diff) + parseFloat(order.restaurant_refund_diff));
        return parseFloat(order.client_refund) + parseFloat(order.restaurant_refund) + parseFloat(order.client_refund_diff) + parseFloat(order.restaurant_refund_diff) > parseFloat(order.total);
    };

    $scope.getOrders = function() {
        apiService.get('get-order-list',
        {
            client_key: client_key,
            api_token : api_token
        }).success(function (data) {
            $scope.orders = data['orders'];
            $scope.startTimer();
        })
        .error(function (status_code, error_message) {

        });
    };

    $scope.getOrders();

    var showPopup = function() {
        $scope.userSubmitError = '';
        $scope.tableform2.$setPristine(true);
        $('#readyby-popup-open').click();
    };

    var closePopup = function() {
        $('.modal').click();
    };

    $scope.changeOrderStatus = function(order){
        $scope.editedOrder = angular.copy($filter('filter')($scope.orders, { id: order.id })[0]);
        $scope.editedOrder.ready_by            = '';
        $scope.editedOrder.ready_by_time       = '';
        $scope.editedOrder.cancellation_reason = null;

        order.isChanged = true;

        if ($scope.editedOrder.current_status === 'ReadyBy' || $scope.editedOrder.current_status === 'OrderConfirmed'){
            showPopup();
        } else if ($scope.editedOrder.current_status === 'OrderCancelled') {
            showPopup();
        }
        setTimeout(function(){
            initializejuiDatetimePicker();
        },500);

        console.log($scope.editedOrder);
    };

    $scope.$on('timer-stopped', function (event, data){
        $scope.getOrders();
    });

    $scope.save = function (order, index) {
        apiService.post('update-order-status', {
            order_id            : order.id,
            order_status        : order.current_status,
            internal_comment    : order.internal_comment,
            restaurant_comment  : order.restaurant_comment,
            restaurant_charge   : order.restaurant_charge,
            client_cost         : order.client_cost,
            client_received     : order.client_received,
            restaurant_credit   : order.restaurant_credit,
            ready_by            : $scope.editedOrder.ready_by,
            ready_by_time       : $scope.editedOrder.ready_by_time,
            cancellation_reason : $scope.editedOrder.cancellation_reason,
            api_token           : api_token
        })
        .success(function (data) {
            var index = getIndexById(order.id);
            $scope.orders[index] = data;
        })
        .error(function (status_code, error_message) {
        });
    };

    $scope.refund = function (order, index) {
        if ($scope.valid(index, order)) {
            apiService.post('update-order-refund', {
                order_id                    : order.id,
                internal_comment            : order.internal_comment,
                client_refund_diff          : order.client_refund_diff,
                restaurant_refund_diff      : order.restaurant_refund_diff,
                corporate_client_refund     : order.corporate_client_refund,
                corporate_restaurant_refund : order.corporate_restaurant_refund,
                api_token                   : api_token
            })
            .success(function (data) {
                 var index = getIndexById(order.id);
                 $scope.orders[index] = data;
            })
            .error(function (status_code, error_message) {
            });
        }
    };

    $scope.savePopup = function(index){
        closePopup();
    };

    $scope.cancel = function(id){
        $index        = getIndexById(id);
        $currentOrder = $scope.orders[$index];
        $currentOrder.current_status = $currentOrder.status;
        closePopup();
    };

    $scope.info = function(order){
        $window.open('/order/info?id='+order.id, '_blank');
    };

    var getIndexById = function(id){
         return $scope.orders.map(function(x) {return x.id; }).indexOf(id);
    };
});

dineinApp.controller('orderInfoController', function ($scope, $filter, apiService) {
    $scope.isChanged = true;
    $scope.userSubmitError = '';
    $scope.editedUser = {};

    $scope.onlyNumbers = /^\d+$/;

    var api_token = $('#api_token').val();

    $scope.filterOrders = function (order) {
        return order.record_type !== 'Deleted';
    };

    $scope.getOrder = function(id) {
       apiService.get('get-internal-order', {
            order_id: id,
            api_token: api_token
        }).success(function (data) {
            $scope.order = data.order;
            $scope.total_quantity = data.total_quantity;
            $scope.max_cook_time = data.max_cook_time;
        })
        .error(function (status_code, error_message) {

        });
    };

    $scope.getOrder(order_id);

    $scope.add = function(type) {
        initilizeEditedUser();
        showPopup();
    };

    $scope.edit = function(id) {
        $scope.editedUser = angular.copy($filter('filter')($scope.users, {id: id})[0]);
        showPopup();
    };

    $scope.save = function (order) {
        apiService.post('update-order-status',
            {
                order_id: order.id,
                order_status: order.current_status,
                api_token: api_token
            })
            .success(function (data) {
                var index = getIndexById(order.id);
                $scope.orders[index] = data;
            })
            .error(function (status_code, error_message) {
            });
    };

    var getIndexById = function(id){
         return $scope.orders.map(function(x) {return x.id; }).indexOf(id);
    };

    $scope.hasUserSubmitError = function() {
        return $scope.userSubmitError != '';
    };

    $scope.setStatus = function(id, record_type) {
        $scope.editedUser = $filter('filter')($scope.users, {id: id})[0];
        $scope.editedUser.record_type = record_type;
        $scope.save();
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
});