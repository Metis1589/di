var restaurantId = location.search.split('id=')[1];

dineinApp.controller('deliveryController', function ($scope, $http, $filter) {

    console.log('delivery', restaurantDeliveryModel);

    $http.get('/restaurant-delivery/get-delivery', {params: {id: restaurantId, model: restaurantDeliveryModel}}).success(function (response) {
        $scope.delivery = response.deliveryService;
        $scope.parentDelivery = response.parentDeliveryService;
        if ($scope.delivery.id != null) {
            $scope.deliveryIsActive = response.deliveryService.record_type == 'Active';
        } else {
            $scope.delivery.record_type = 'Inactive';
        }

        initializeCharges();
    });

    $scope.deliveryFormIsSubmitting = false;
    $scope.submitError = '';

    // save edits
    $scope.saveDelivery = function () {
        $scope.deliveryFormIsSubmitting = true;
        $http.post('/restaurant-delivery/save-delivery', {
            id: restaurantId,
            model: restaurantDeliveryModel,
            delivery: $scope.delivery
        }).success(function (response) {
            $scope.delivery = response.deliveryService;
            $scope.parentDelivery = response.parentDeliveryService;

            if ($scope.delivery.errors != undefined) {
                $scope.submitError = $scope.delivery.errors.join();
            } else {
                $scope.submitError != '';
            }

            initializeCharges();
            $scope.deliveryFormIsSubmitting = false;

        }).error(function (data) {
            $scope.deliveryFormIsSubmitting = false;
            $scope.submitError = data;
        });
    };

    $scope.activateDeliveryService = function() {
        if ($scope.deliveryIsActive) {
            var deliveryId = $scope.delivery.id;
            $scope.delivery = angular.copy($scope.parentDelivery);
            $scope.delivery.id = deliveryId;
            $scope.delivery.record_type = 'Active';
        } else {
            $scope.delivery.record_type = 'InActive';
        }
    }

    $scope.hasSubmitError = function () {
        return $scope.submitError != '';
    }

    $scope.chargeFilterOptions = function (option) {
        return option.record_type !== 'Deleted';
    };

    $scope.showDeleteRow = function () {
        var c = 0;
        for(var i = 0; i < $scope.delivery.restaurantDeliveryCharges.length; i++) {
            if ($scope.delivery.restaurantDeliveryCharges[i].record_type!== 'Deleted') {
                c++;
            }
            if (c > 1) {
                return true;
            }
        }

        return false;
    }

    $scope.deleteCharge = function(id) {
        var filtered = $filter('filter')($scope.delivery.restaurantDeliveryCharges, {id: id});
        if (filtered.length) {
            filtered[0].record_type = 'Deleted';
        }
    };

    // add user
    $scope.addCharge = function() {
        $scope.delivery.restaurantDeliveryCharges.push({
            id: getNextOptionId(),
            distance_in_miles: '',
            charge: '',
            is_new: true,
        });
    };

    // cancel all changes
    $scope.cancel = function () {
        for (var i = $scope.delivery.restaurantDeliveryCharges.length; i--; ) {
            var option = $scope.delivery.restaurantDeliveryCharges[i];
            // undelete
            if (option.record_type === 'Deleted') {
                delete option;
            }
            // remove new
            if (option.is_new) {
                $scope.delivery.restaurantDeliveryCharges.splice(i, 1);
            }
        };
    };

    var getMaxOptionId = function() {
        if ($scope.delivery.restaurantDeliveryCharges.length == 0) {
            return -1;
        }
        ids = [];
        for (var i = $scope.delivery.restaurantDeliveryCharges.length; i--; ) {
            ids.push($scope.delivery.restaurantDeliveryCharges[i].id);
        }
        return Math.max.apply(Math, ids);
    };

    var getNextOptionId = function() {
        return getMaxOptionId() + 1;
    };

    var initializeCharges = function() {
        if ($scope.delivery.restaurantDeliveryCharges == undefined || $scope.delivery.restaurantDeliveryCharges.length == 0) {
            $scope.delivery.restaurantDeliveryCharges = [];
            $scope.addCharge();
        }
    }
});

dineinApp.controller('scheduleController', function ($scope, $http, $filter) {
    $scope.scheduleFormIsSubmitting = false;
    $scope.scheduleSubmitError = '';

    $http.get('/restaurant-schedule/get-schedule', {params: {id: restaurantId, model: restaurantScheduleModel}}).success(function (response) {
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
        $http.post('/restaurant-schedule/save-schedule', {
            id: restaurantId,
            model: restaurantScheduleModel,
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

dineinApp.controller('orderContactsController', function ($scope, $http, $filter) {
    $scope.contactOrderFormIsSubmitting = false;
    $scope.contactOrderSubmitError = '';
    $scope.editedContact = {};

    $http.get('/restaurant-contact-order/get-contacts', {params: {restaurant_id: restaurantId}}).success(function (response) {
        $scope.contacts = response;
    });

    $scope.add = function(type) {
        $scope.editedContact = {
            id: 0,
            type: type,
            charge: ''
        };
        showPopup();
    }

    $scope.edit = function(id) {
        $scope.editedContact = angular.copy($filter('filter')($scope.contacts, {id: id})[0]);
        showPopup();
    };

    $scope.save = function () {
        $scope.contactOrderFormIsSubmitting = true;
        $http.post('/restaurant-contact-order/save', {
            restaurant_id: restaurantId,
            contact: $scope.editedContact
        }).success(function (response) {

            if (response == 'Error') {
                $scope.contactOrderSubmitError = response;
            } else {
                $scope.contactOrderSubmitError = '';
                var index = getIndexById(response.id);
                if (index > -1) {
                    $scope.contacts[index] = response;
                } else {
                    $scope.contacts.push(response);
                }
                $scope.editedContact = {};
                closePopup();
            }
            $scope.contactOrderFormIsSubmitting = false;


        }).error(function (data) {
            $scope.contactOrderFormIsSubmitting = false;
            $scope.contactOrderSubmitError = data;
        });
    };

    $scope.hasContactOrderSubmitError = function() {
        return $scope.contactOrderSubmitError != '';
    }

    $scope.filterContacts = function (type) {
        return function(contact) {
            return contact.record_type !== 'Deleted' && contact.type == type;
        }
    };

    $scope.setStatus = function(id, record_type) {
        $scope.editedContact = $filter('filter')($scope.contacts, {id: id})[0];
        $scope.editedContact.record_type = record_type;
        $scope.save();
    }

    var showPopup = function() {
        $scope.contactOrderSubmitError = '';
        $scope.tableform.$setPristine(true);
        console.log($scope.tableform);
        $('#contact-popup-open').click();
    }

    var closePopup = function() {
        console.log('closePopup');
        $('.modal').click();
    }

    var getIndexById = function(id){
        return $scope.contacts.map(function(x) {return x.id; }).indexOf(id);
    };

});

dineinApp.controller('paymentController', function ($scope, $http, $filter) {
    $scope.paymentFormIsSubmitting = false;
    $scope.paymentSubmitError = '';
    $scope.payment = {
        id: 0
    }

    $http.get('/restaurant-payment/get-payment', {params: {restaurant_id: restaurantId}}).success(function (response) {
        if (response != 'null') {
            $scope.payment = response;
        }

    });

    $scope.save = function () {
        $scope.paymentFormIsSubmitting = true;
        $http.post('/restaurant-payment/save', {
            restaurant_id: restaurantId,
            payment: $scope.payment
        }).success(function (response) {

            if (response == 'Error') {
                $scope.paymentSubmitError = response;
            } else {
                $scope.paymentSubmitError = '';
                $scope.payment = response;
            }
            $scope.paymentFormIsSubmitting = false;
        }).error(function (data) {
            $scope.paymentFormIsSubmitting = false;
            $scope.paymentSubmitError = data;
        });
    };

    $scope.hasPaymentSubmitError = function() {
        return $scope.paymentSubmitError != '';
    }
});

dineinApp.controller('userController', function ($scope, $http, $filter, $element) {
    console.log('userController', $($element).attr('load-url'));
    $scope.userFormIsSubmitting = false;
    $scope.userSubmitError      = '';
    $scope.editedUser           = {};

    $('[data-users]').on('click', function() {
        $http.get($($element).attr('load-url')).success(function (response) {
            $scope.users = response;
        });
    });

    $scope.filterUsers = function (user) {
        return user.record_type !== 'Deleted';
    };

    $scope.add = function(popupId) {
        console.log('add');
        initilizeEditedUser();
        showPopup(popupId);
    };

    $scope.edit = function(id, popupId) {
        $scope.editedUser = angular.copy($filter('filter')($scope.users, { id: id })[0]);
        showPopup(popupId);
    };

    $scope.save = function () {
        $scope.userFormIsSubmitting = true;
        $http.post('/user/save', {
            user: $scope.editedUser
        }).success(function (response) {
            $scope.tableform.$setPristine(true);
            if (response.errors != undefined) {
                $scope.userSubmitError       = response.errors[0].join();;
            } else {
                $scope.userSubmitError = '';
                var index = getIndexById(response.id);
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

    $scope.isPasswordRequired = function() {
        console.log('isPasswordRequired');
        return true;
    };

    $scope.isRePasswordRequired = function() {
        return true;
    };

    $scope.hasUserSubmitError = function() {
        return $scope.userSubmitError != '';
    };

    $scope.setStatus = function(id, record_type) {
        $scope.editedUser = $filter('filter')($scope.users, {id: id})[0];
        $scope.editedUser.record_type = record_type;
        $scope.save();
    };

    var showPopup = function(popupId) {
        $scope.userSubmitError = '';
        $scope.tableform.$setPristine(true);
        $('#' + popupId).click();
        if (!$scope.editedUser.company_user_group_id) {
            $scope.editedUser.company_user_group_id = $('#company_user_group_id option[selected=""]').val();
        }
    };

    var initilizeEditedUser = function() {
        $scope.editedUser = {
            id                   : 0,
            restaurant_id        : restaurantId,
            first_name           : '',
            last_name            : '',
            record_type          : '',
            company_user_group_id: '',
            user_type            : 'CorporateMember',
            is_corporate_approved: 0,
            user_title           : '',
            primaryAddress       : {
                address1             : '',
                address2             : '',
                city                 : '',
                postcode             : '',
                phone                : ''
            }
        };
    };

    var closePopup = function() {
        $('.modal').click();
    };

    var getIndexById = function(id){
        return $scope.users.map(function(x) {return x.id; }).indexOf(id);
    };

});

dineinApp.controller('cuisineBestForItemController', function ($scope, $http, $filter) {
    $scope.isSubmitting = false;

    $scope.assingCuisine = function(id) {
        $scope.isSubmitting = true;
        var isAssigned = !$('#cuisine_' + id).is(':checked');
        var cuisine = prepareCuisine(isAssigned, id);
        $http.post('/restaurant/assign-cuisine', cuisine).success(function (response) {
            if (response == 'false') {
                $('#cuisine_' + id).prop('checked', isAssigned);
            }
            $scope.isSubmitting = false;
        }).error(function (data) {
            $('#cuisine_' + id).prop('checked', isAssigned);
            $scope.isSubmitting = false;
        });
    }

    $scope.assingBestForItem = function(id) {
        $scope.isSubmitting = true;
        var isAssigned = !$('#best_for_item_' + id).is(':checked');
        var bestForItem = prepareBestForItem(isAssigned, id);
        $http.post('/restaurant/assign-best-for-item', bestForItem).success(function (response) {
            if (response == 'false') {
                $('#best_for_item_' + id).prop('checked', isAssigned);
            }
            $scope.isSubmitting = false;
        }).error(function (data) {
            $('#best_for_item_' + id).prop('checked', isAssigned);
            $scope.isSubmitting = false;
        });
    }

    var prepareCuisine = function(isAssigned, cuisineId) {
        return {
            cuisine_id: cuisineId,
            restaurant_id: restaurantId,
            record_type: isAssigned ? 'Inactive' : 'Active'
        }
    }

    var prepareBestForItem = function(isAssigned, cuisineId) {
        return {
            best_for_item_id: cuisineId,
            restaurant_id: restaurantId,
            record_type: isAssigned ? 'Inactive' : 'Active'
        }
    }
});
dineinApp.controller('orderExportController',function($scope, $http,$timeout){
    var modelId = restaurantId,
         modelName = restaurantDeliveryModel;
    $scope.orderExportIsActive = false;
    $scope.formSubmitting = true;
    $scope.submitError = '';

    $http
         .get('/order-export/get-order-export', {params: {id: modelId, model: modelName}})
         .success(function (response) {
             $scope.export = response;
             $scope.orderExportIsActive = response && response.length > 0
                  && response[0]
                  && 'record_type' in response[0]
                  && response[0].record_type == 'Active';
             $scope.formSubmitting = false;
         });
    $scope.saveExport = function(){
        $scope.formSubmitting = true;
        $http.post('/order-export/save', {
            id: modelId,
            model: modelName,
            export: $scope.export
        }).success(function (response) {
            $timeout(function(){
                if(response && 'result' in response){
                    if(response.result == 'Success'){
                        $scope.export = response.export;
                    }else{
                        $scope.submitError = 'Can\'t save exports';
                    }
                }
                $scope.formSubmitting = false;
            });

        }).error(function (data) {
            $timeout(function(){
                $scope.formSubmitting = false;
                $scope.submitError = data;
            });
        });
    };
    $scope.addNewConfig = function(){
        var newExport = angular.copy($scope.export[0]);
        for(var key in newExport){
            newExport[key] = '';
        }
        $scope.export.push(newExport);
    };
    $scope.removeConfig = function(id,index){
        if(id){
            $http.post('/order-export/delete', {
                id: modelId,
                model: modelName,
                exportId: id
            }).success(function (response) {
                $timeout(function(){
                    if(response && 'result' in response){
                        if(response.result == 'Success'){
                            $scope.export = response.export;
                        }else{
                            $scope.submitError = 'Can\'t save exports';
                        }
                    }
                    $scope.formSubmitting = false;
                });

            }).error(function (data) {
                $timeout(function(){
                    $scope.formSubmitting = false;
                    $scope.submitError = data;
                });
            });
        }else if(index === 0 || index){
            $scope.export = $scope.export.filter(function(e,i){
                return i !== index;
            })
        }
    };
    $scope.switchType = function(active,inactive)
    {
        if($scope.export.length > 0){
            var newType = $scope.export[0].record_type == active ? inactive : active;
            angular.forEach($scope.export, function(el){
                el.record_type = newType;
            })
        }
    }
});

var parseTime = function(timeStr) {
    if (timeStr == null) {
        return null;
    }
    var d = new Date();
    var time = timeStr.match(/(\d+)(?::(\d\d))?\s*(p?)/);
    if (time == null || time.length != 4) {
        return null;
    }
    d.setHours( parseInt(time[1]) + (time[3] ? 12 : 0) );
    d.setMinutes( parseInt(time[2]) || 0 );
    return d;
}