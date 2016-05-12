/**
 * Created by jarik on 5/18/15.
 */

dineinApp.directive('deliveryType', function ($timeout,$rootScope) {
    return {
        restrict: 'E',
        replace: true,
        interpolate:true,
        scope: {
            openClass:'=',
            typeList: '=',
            dateList: '=',
            deliveryDate: "=",
            deliveryTime: "=",
            //selectedType: '@',
            onSelect: '&',
            type:'=',
            elementId:'@',
            close:'=',
            onOpen:'&',
            getSelection:'&'
        },
        controller: function ($scope,$element) {
            var self = this;
            if(angular.isUndefined($scope.elementId)){
                $scope.elementId = 'delivery_asap';
            }

            $scope.isDateType = function (type) {
                return !angular.isUndefined(type) && type.search('Later') > -1;
            };

            $scope.selectDeliveryOpened = false;
            $scope.selectTimeOpened = false;
            if(!angular.isUndefined($scope.typeList)){
                $scope.typeOrder = Object.keys($scope.typeList);
            }

            function setSelectedType(){
                if($scope.type === "null" || $scope.type === null || angular.isUndefined($scope.type)){
                    if(!angular.isUndefined($scope.typeList)){
                        $scope.selectedType = Object.keys($scope.typeList)[0];
                    }else if(angular.isArray($scope.typeOrder) && $scope.typeOrder.length >0){
                        $scope.selectedType = $scope.typeOrder[0];
                    }
                }else{
                    $scope.selectedType = $scope.type
                }
            }
            $scope.$watch('type',function(oldVal,newVal,$scope){
                setSelectedType();
            });
            // Prepare dates and times
            var dates = {};
            var dateTime = {};
            for(var dateKey in $scope.dateList){
                dates[dateKey]=dateKey;
                dateTime[dateKey] = {};
                for(var key in $scope.dateList[dateKey] ){
                    var time = $scope.dateList[dateKey][key];
                    dateTime[dateKey][time] = time;
                }
            }
            $scope.dates = dates;
            $scope.dateTime = dateTime;
            if(!angular.isUndefined($scope.selectedType)){
                savePrevious();
            }
            function setCssClasses(){
                $scope.cssClass = $scope.selectDeliveryOpened == true ? $scope.openClass : '';
            }
            setCssClasses();
            $scope.selectDeliveryType = function ($event, type) {
                $event.stopImmediatePropagation();
                if (type in $scope.typeList) {
                    if($scope.selectedType == type){
                        if ($scope.isDateType(type)) {
                            $scope.openSelectTime();
                        } else {
                            notifySelect();
                        }
                    }else{
                        $scope.selectedType = type;
                        //$scope.selectDeliveryOpened = false;
                        $scope.deliveryTime = $scope.deliveryDate = null;
                        if ($scope.isDateType(type)) {
                            $scope.openSelectTime();
                        } else {
                            notifySelect();
                        }
                    }
                }
            };

            $scope.openSelectDelivery = function ($event) {
                $event.stopPropagation();
                $scope.selectDeliveryOpened = true;
                //$($element).find('ul.main_page_submenu').slideDown(500);
                setCssClasses();
                if(angular.isFunction($scope.onOpen)){
                    $scope.onOpen();
                }
            };
            $scope.openSelectTime = function () {
                $scope.selectDeliveryOpened = true;
                $scope.selectTimeOpened = true;
            };
            $scope.selectDate = function (date) {
                $scope.deliveryDate = date;
                $scope.deliveryTime = null;
                isAllSet() && notifySelect();
            };

            $scope.selectTime = function (time) {
                $scope.deliveryTime = time;
                isAllSet() && notifySelect();
            };

            $scope.getTypeClass = function (type) {
                var typeClass = {
                    CollectionAsap: 'collect_asap_set',
                    CollectionLater: 'collect_later_set',
                    DeliveryLater: 'delivery_later_set',
                    DeliveryAsap: 'delivery_asap_set'
                };
                if (type in typeClass) {
                    return typeClass[type];
                }
                return;
            };
            this.externalEventHandler = function (){
                if($scope.selectDeliveryOpened === true) {
                    $scope.$apply(function () {
                        if ($scope.selectDeliveryOpened === true) {
                            revertType();
                            self.closeFilter();
                        }
                    });
                }
            };
            $scope.spanClicked = function($event){
                $event.stopPropagation();
                $scope.selectTimeOpened = false;
            };

            if(angular.isFunction($scope.close)){
                $scope.close = function(){
                    self.externalEventHandler();
                };
            }

            this.closeFilter = function (){
                $scope.selectDeliveryOpened = false;
                $scope.selectTimeOpened = false;
                setCssClasses();
                //$($element).find('ul.main_page_submenu').slideUp(500);
            };
            // Validate is all fields is set
            function isAllSet() {
                var result = false;
                if ($scope.selectedType) {
                    if($scope.isDateType($scope.selectedType)){
                        if($scope.deliveryDate && $scope.deliveryTime){
                            result = true;
                        }else{
                            result = false;
                        }
                    }else{
                        result = true;
                    }
                }
                return result;
            }
            function savePrevious(){
                if($scope.isDateType($scope.selectedType)){
                    $scope.previous = {
                        selectedType: $scope.selectedType,
                        deliveryDate: $scope.deliveryDate,
                        deliveryTime: $scope.deliveryTime
                    };
                }else{
                    $scope.previous = {
                        selectedType: $scope.selectedType
                    };
                }
            }
            function notifySelect() {
                self.closeFilter();
                if ($scope.isDateType($scope.selectedType)) {
                    if ($scope.deliveryDate && $scope.deliveryTime) {
                        savePrevious();
                        var data = {
                            deliveryType: $scope.selectedType,
                            deliveryDate: $scope.deliveryDate,
                            deliveryTime: $scope.deliveryTime
                        };
                        $scope.onSelect({
                            data: data
                        });
                        $scope.$emit('NEW_DELIVERY_TYPE_SELECTED',data);
                    }
                } else {
                    savePrevious();
                    var data = {
                        deliveryType: $scope.selectedType
                    };
                    $scope.onSelect({
                        data: data
                    });
                    $scope.$emit('NEW_DELIVERY_TYPE_SELECTED',data);
                }
            }

            function revertType() {
                $scope.selectedType = $scope.previous.selectedType;
                if($scope.isDateType($scope.previous.selectedType)){
                    $scope.deliveryDate = $scope.previous.deliveryDate;
                    $scope.deliveryTime = $scope.previous.deliveryTime;
                }
            }
        },
        link:function($scope,$elem,attrs,ctrl){
            $(document).on('click',function(e){
                ctrl.externalEventHandler();
            });
        },
        templateUrl: '/custom/js/templates/deliveryType.html'
    }
});