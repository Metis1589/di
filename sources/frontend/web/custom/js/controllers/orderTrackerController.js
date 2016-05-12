dineinApp.controller('orderTrackerController', function($scope, $interval, orderService) {
    $scope.status = '';
    $scope.estimated_time = '';
    $scope.progress = 0;
    $scope.restaurant_name = '';
    $scope.order_number = null;
    $scope.restaurant_delivery = null;
    $('.chart').easyPieChart({
        barColor: '#F58426',
        scaleColor: false,
        trackColor: false,
        lineWidth: 10,
        lineCap: 'butt',
        animate: 1000,
        size: 300,
        rotate: 10
    });
    $scope.initOrderNumber = function(number){
        if(angular.isNumber(number)){
            $scope.order_number = number;
            getOrderStatus();
        }
    }
    $scope.startTrack = function($event){
        $event.stopPropagation();
        getOrderStatus();
    };
    $scope.keypressHandler = function($event){
        $event.stopPropagation();
        if($event.keyCode == 13){
            getOrderStatus();
        }
    };
    /**
     * get progress by status
     * @param status
     * @returns {number}
     */
    var getProgress = function(status) {

        switch (status) {
            case 'Delivered':
            case 'Collected':
            case 'ArrivedAtCustomer':
                return 100-2.9;
            case 'FoodEnRoute':
                return 87.5-4.1;
            case 'DriverPickedUp':
            case 'DriverWaiting':
            case 'DriverAtRestaurant':
            case 'EstimatedDeliveryTime':
                return 75-2.7;
            case 'WayToPickUp':
            case 'AcceptByDriver':
            case 'RequestDriver':
            case 'AssignedToDriver':
            case 'FoodIsReady':
            case 'FoodPreparing':
                return 62.5 - 1.4;
            case 'OrderConfirmed':
            case 'ReadyBy':
                return 50-2.7;
            case 'TransferringToRestaurant':
                return 37.5-4.1;
            case 'PaymentReceived':
                return 25-2.7;
            case 'ProcessingPayment':
                return 12.5-1.4;
            default:
                return 0;
        }
    };

    /**
     * update progress
     * @param status
     */
    var updateProgress = function(status) {
        $scope.progress = getProgress(status);

        $('.chart').data('easyPieChart').update($scope.progress);

        if($scope.progress >= 12.5-1.4) $('.processing').addClass('current_progress');
        if($scope.progress >= 25-2.7) $('.received').addClass('current_progress');
        if($scope.progress >= 37.5-4.1) $('.transferring').addClass('current_progress');
        if($scope.progress >= 50-2.7) $('.confirmed').addClass('current_progress');
        if($scope.progress >= 62.5 - 1.4) $('.prepared').addClass('current_progress');
        if($scope.progress >= 75-2.7) $('.estimated_delivery_time').addClass('current_progress');
        if($scope.progress >= 87.5-4.1) $('.food_en_route').addClass('current_progress');
        if($scope.progress == 100-2.9) $('.your_meal').addClass('current_progress');
    };

    /**
     * Load order status
     */
    var getOrderStatus = function() {
        if($scope.order_number !== null && /^[0-9]+$/.test($scope.order_number)){
            orderService.getOrderStatus($scope.order_number, clearOrder)
                .success(function (data) {
                     if(data == null && updatePromise){
                         $interval.cancel(updatePromise);
                         $scope.progress = 0;
                         updateProgress();
                         $scope.estimated_time = '';
                         $scope.restaurant_name= '';
                         $scope.restaurant_phone = '';
                     }else{
                         if('status' in data){
                             $scope.status = data.status;
                             updateProgress(data.status);
                         }
                         if('later_date_to' in data && data.later_date_to){
                             $scope.estimated_time = data.later_date_to;
                         }
                         if('restaurant_name' in data){
                             $scope.restaurant_name = data.restaurant_name;
                         }
                         if('restaurant_phone' in data){
                             $scope.restaurant_phone = data.restaurant_phone;
                         }
                         if('restaurant_delivery' in data){
                             $scope.restaurant_delivery = data.restaurant_delivery;
                         }
                     }

                });
        }
    };

    var updatePromise = $interval(function() {
        getOrderStatus();
    }, 5000);

    getOrderStatus();

});