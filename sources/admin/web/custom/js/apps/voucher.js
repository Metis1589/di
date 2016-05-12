dineinApp.controller('voucherScheduleController', function ($scope, $http, $filter) {
    $scope.scheduleFormIsSubmitting = false;
    $scope.scheduleSubmitError = '';

    $http.get('/voucher-schedule/get-schedule', {params: {id: voucherId}}).success(function (response) {
        $scope.schedules = response;
        for(var i = 0; i < $scope.schedules.length; i++) {
            if ($scope.schedules[i].record_type == 'Active') {
                $scope.scheduleIsActive = true;
                break;
            }
        }
        setTimeout(function() {
            $('.time-picker-tab').timepicker();
        }, 500)
    });

    $scope.saveSchedule = function () {
        $scope.scheduleFormIsSubmitting = true;
        $http.post('/voucher-schedule/save-schedule', {
            id: voucherId,
            schedules: $scope.schedules
        }).success(function (response) {

            if (response.result != 'Success') {
                $scope.scheduleSubmitError = response.response;
            } else {
                $scope.scheduleSubmitError = '';
                console.log(response.schedules);
                $scope.schedules = response.schedules;
                setTimeout(function() {
                    $('.time-picker-tab').timepicker();
                }, 500)

            }
            $scope.scheduleFormIsSubmitting = false;

        }).error(function (data) {
            $scope.scheduleFormIsSubmitting = false;
            $scope.scheduleSubmitError = data;
        });
    };

    $scope.disableSchedule = function(active, inactive) {
        for(var i = 0; i < $scope.schedules.length; i++) {
            $scope.schedules[i].record_type = $scope.scheduleIsActive ? active : inactive;
        }
        setTimeout(function() {
            $('.time-picker-tab').timepicker();
        }, 500)
    }

    $scope.hasSubmitScheduleError = function () {
        return $scope.scheduleSubmitError != '';
    }
});

