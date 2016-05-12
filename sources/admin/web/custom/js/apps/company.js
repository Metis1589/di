
/**
 * Corporate members
 */
var companyId = location.search.split('id=')[1];

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
            company_id           : companyId,
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

/**
 * Corporate administrators
 */
dineinApp.controller('adminController', function ($scope, $http, $filter) {
    $scope.userFormIsSubmitting = false;
    $scope.userSubmitError      = '';
    $scope.editedUser           = {};

    $('[data-admins]').on('click', function() {
        $http.get('/user/get-company-users', {params: { role: 'CorporateAdmin', id: companyId }}).success(function (response) {
            $scope.users = response;
        });
    });

    $scope.filterUsers = function (user) {
        return user.record_type !== 'Deleted';
    };

    $scope.add = function(type) {
        initilizeEditedUser();
        showPopup();
    };

    $scope.edit = function(id) {
        $scope.editedUser = angular.copy($filter('filter')($scope.users, { id: id })[0]);
        showPopup();
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

    var showPopup = function() {
        $scope.userSubmitError = '';
        $scope.tableform.$setPristine(true);
        $('#user-popup-open-company_admin').click();
    };

    var initilizeEditedUser = function() {
        $scope.editedUser = {
            id                   : 0,
            company_id           : companyId,
            first_name           : '',
            last_name            : '',
            title                : '',
            record_type          : '',
            user_type            : 'CorporateAdmin',
            is_corporate_approved: 0
        };
    };

    var closePopup = function() {
        $('.modal').click();
    };

    var getIndexById = function(id){
        return $scope.users.map(function(x) {return x.id; }).indexOf(id);
    };

});

/**
 * Company domains
 */
dineinApp.controller('domainsController', function ($scope, $http, $filter) {
    $scope.domainsFormIsSubmitting = false;
    $scope.domainsSubmitError      = '';
    $scope.editedDomain            = {};

    $http.get('/company-domain/get-company-domains', {params: { id: companyId }}).success(function (response) {
        $scope.domains = response;
    });

    $scope.filterDomains = function (domain) {
        return domain.record_type !== 'Deleted';
    };

    $scope.add = function() {
        initilizeEditedDomain();
        showPopup();
    };

    $scope.edit = function(id) {
        $scope.editedDomain = angular.copy($filter('filter')($scope.domains, { id: id })[0]);
        showPopup();
    };

    $scope.save = function () {
        $scope.domainFormIsSubmitting = true;
        $http.post('/company-domain/save', {
            domain: $scope.editedDomain
        }).success(function (response) {
            $scope.tableform.$setPristine(true);
            if (response.errors != undefined) {
                $scope.domainSubmitError = response.errors[0].join();
            } else {
                $scope.domainSubmitError = '';
                var index = getIndexById(response.id);
                if (index > -1) {
                    $scope.domains[index] = response;
                } else {
                    $scope.domains.push(response);
                }
                initilizeEditedDomain();
                closePopup();
            }
            $scope.domainFormIsSubmitting = false;


        }).error(function (data) {
            $scope.domainFormIsSubmitting = false;
            $scope.domainSubmitError      = data;
        });
    };

    $scope.hasDomainSubmitError = function() {
        return $scope.domainSubmitError != '';
    };

    $scope.setStatus = function(id, record_type) {
        $scope.editedDomain = $filter('filter')($scope.domains, { id: id })[0];
        $scope.editedDomain.record_type = record_type;
        $scope.save();
    };

    var showPopup = function() {
        $scope.domainSubmitError = '';
        $scope.tableform.$setPristine(true);
        $('#domain-popup-open').click();
    };

    var initilizeEditedDomain = function() {
        $scope.editedDomain = {
            id         : 0,
            company_id : companyId,
            domain     : '',
            record_type: ''
        };
    };

    var closePopup = function() {
        $('.modal').click();
    };

    var getIndexById = function(id){
        return $scope.domains.map(function(x) { return x.id; }).indexOf(id);
    };

});

/**
 * Company user groups
 */
dineinApp.controller('groupsController', function ($scope, $http, $filter) {
    $scope.groupsFormIsSubmitting = false;
    $scope.groupsSubmitError      = '';
    $scope.editedGroup            = {};

    $('[data-groups]').on('click', function() {
        $http.get('/company-user-group/get-company-groups', {params: { id: companyId }}).success(function (response) {
            $scope.groups = response.groups;
            $scope.codes  = response.codes;
        });
    });

    $scope.filterGroups = function (group) {
        return group.record_type !== 'Deleted';
    };

    $scope.add = function() {
        initilizeEditedGroup();
        showPopup();
    };

    $scope.edit = function(id) {
        $scope.editedGroup       = angular.copy($filter('filter')($scope.groups, { id: id })[0]);
        $scope.editedGroup.codes = $scope.codes;

        if ($scope.editedGroup.companyUserGroupCodeNames.length) {
            $scope.editedGroup.codes.forEach(function(idx, value) {
                $scope.editedGroup.codes[value].isChecked = false;
                $scope.editedGroup.companyUserGroupCodeNames.forEach(function(idxC, valueC) {
                    if ($scope.editedGroup.codes[value].id === $scope.editedGroup.companyUserGroupCodeNames[valueC].id) {
                        $scope.editedGroup.codes[value].isChecked = true;
                    }
                });
            });
        } else {
            $scope.editedGroup.codes.forEach(function(idx, value) {
                $scope.editedGroup.codes[value].isChecked = false;
            });
        }
        showPopup();
    };

    $scope.save = function () {
        var currentGroups = [];
        $scope.editedGroup.companyUserGroupCodeNames.forEach(function(idxC, valueC) {
            currentGroups.push($scope.editedGroup.companyUserGroupCodeNames[valueC].id);
        });

        if ($scope.editedGroup.codes && $scope.editedGroup.codes.length) {
            $scope.editedGroup.codes.forEach(function(idx, value) {
                // Delete unchecked group codes
                if ($scope.editedGroup.companyUserGroupCodeNames.length) {
                    $scope.editedGroup.companyUserGroupCodeNames.forEach(function(idxC, valueC) {
                        if ($scope.editedGroup.codes[value].id === $scope.editedGroup.companyUserGroupCodeNames[valueC].id && $scope.editedGroup.codes[value].isChecked === false) {
                            $scope.editedGroup.companyUserGroupCodeNames[valueC].isChecked = false;
                        }
                    });
                }
                // Add checked group codes
                if (currentGroups.indexOf($scope.editedGroup.codes[value].id) === -1 && $scope.editedGroup.codes[value].isChecked) {
                    $scope.editedGroup.companyUserGroupCodeNames.push($scope.editedGroup.codes[value]);
                }
            });
        }

        $scope.groupFormIsSubmitting = true;
        $http.post('/company-user-group/save', {
            group: $scope.editedGroup
        }).success(function (response) {
            $scope.tableform.$setPristine(true);
            if (response.errors != undefined) {
                $scope.groupSubmitError = response.errors[0].join();
            } else {
                $scope.groupSubmitError = '';
                $scope.groups = response;
                initilizeEditedGroup();
                closePopup();
            }
            $scope.groupFormIsSubmitting = false;


        }).error(function (data) {
            $scope.groupFormIsSubmitting = false;
            $scope.groupSubmitError      = data;
        });
    };

    $scope.hasGroupSubmitError = function() {
        return $scope.groupSubmitError != '';
    };

    $scope.setStatus = function(id, record_type) {
        $scope.editedGroup = $filter('filter')($scope.groups, { id: id })[0];
        $scope.editedGroup.record_type = record_type;
        $scope.save();
    };

    $scope.resetUserGroup = function(group_id, id) {
        $scope.editedGroup = $filter('filter')($scope.groups, { id: group_id })[0];
        $filter('filter')($scope.editedGroup.companyUserGroupUsers, { id: id })[0].company_user_group_id = null;
        $scope.save();
    };

    var showPopup = function() {
        $scope.groupSubmitError = '';
        $scope.tableform.$setPristine(true);
        $('#group-popup-open').click();
    };

    var initilizeEditedGroup = function() {
        $scope.editedGroup = {
            id                        : 0,
            company_id                : companyId,
            name                      : '',
            record_type               : '',
            max_order_per_day_per_user: '',
            codes                     : $scope.codes,
            companyUserGroupUsers     : [],
            companyUserGroupCodeNames : []
        };
    };

    var closePopup = function() {
        $('.modal').click();
    };

    var getIndexById = function(id){
        return $scope.groups.map(function(x) { return x.id; }).indexOf(id);
    };

});

/**
 * Company code codes
 */
dineinApp.controller('codesController', function ($scope, $http, $filter) {
    $scope.codesFormIsSubmitting = false;
    $scope.codesSubmitError      = '';
    $scope.editedCode            = {};

    $('[data-codes]').on('click', function() {
        $http.get('/company-user-group-code/get-company-codes', {params: { id: companyId }}).success(function (response) {
            $scope.codes = response;
        });
    });

    $scope.filterCodes = function (code) {
        return code.record_type !== 'Deleted';
    };

    $scope.add = function() {
        initilizeEditedCode();
        showPopup();
    };

    $scope.edit = function(id) {
        $scope.editedCode = angular.copy($filter('filter')($scope.codes, { id: id })[0]);
        showPopup();
    };

    $scope.save = function () {
        $scope.codeFormIsSubmitting = true;
        $http.post('/company-user-group-code/save', {
            code: $scope.editedCode
        }).success(function (response) {
            $scope.tableform.$setPristine(true);
            if (response.errors != undefined) {
                $scope.codeSubmitError = response.errors[0].join();
            } else {
                $scope.codeSubmitError = '';
                var index = getIndexById(response.id);
                if (index > -1) {
                    $scope.codes[index] = response;
                } else {
                    $scope.codes.push(response);
                }
                initilizeEditedCode();
                closePopup();
            }
            $scope.codeFormIsSubmitting = false;


        }).error(function (data) {
            $scope.codeFormIsSubmitting = false;
            $scope.codeSubmitError      = data;
        });
    };

    $scope.hasCodeSubmitError = function() {
        return $scope.codeSubmitError != '';
    };

    $scope.setStatus = function(id, record_type) {
        $scope.editedCode = $filter('filter')($scope.codes, { id: id })[0];
        $scope.editedCode.record_type = record_type;
        $scope.save();
    };

    var showPopup = function() {
        $scope.codeSubmitError = '';
        $scope.tableform.$setPristine(true);
        $('#code-popup-open').click();
    };

    var initilizeEditedCode = function() {
        $scope.editedCode = {
            id           : 0,
            company_id   : companyId,
            name         : '',
            value        : '',
            daily_limit  : '',
            weekly_limit : '',
            monthly_limit: '',
            limit_type   : '',
            record_type  : ''
        };
    };

    var closePopup = function() {
        $('.modal').click();
    };

    var getIndexById = function(id){
        return $scope.codes.map(function(x) { return x.id; }).indexOf(id);
    };

});

/**
 * Company expense types
 */
dineinApp.controller('extypesController', function ($scope, $http, $filter) {
    $scope.extypesFormIsSubmitting = false;
    $scope.extypesSubmitError      = '';
    $scope.editedExtype            = {};


    $('[data-exptypes]').on('click', function() {
        $http.get('/expense-type/get-company-expense-types', {params: { id: companyId }}).success(function (response) {
            $scope.extypes = response;
        });
    });


    $scope.filterExtypes = function (code) {
        return code.record_type !== 'Deleted';
    };

    $scope.add = function() {
        $http.get('/expense-type/get-schedule', {params: { company_id: companyId, clear: true }}).success(function (response) {
            $scope.schedules = response.schedule;
            $scope.groups    = response.groups;
            $scope.editedExtype.schedules = $scope.schedules;
            setTimeout(function() {
                $('.timepicker').timepicker();
            }, 500);
        });
        initilizeEditedExtype();
        showPopup();
    };

    $scope.edit = function(id) {
        $http.get('/expense-type/get-schedule', {params: { company_id: companyId, schedule_id: id }}).success(function (response) {
            $scope.schedules = response.schedule;
            $scope.groups    = response.groups;
            $scope.editedExtype.schedules = $scope.schedules;
            setTimeout(function() {
                $('.timepicker').timepicker();
            }, 500);
        });

        $scope.editedExtype = angular.copy($filter('filter')($scope.extypes, { id: id })[0]);
        showPopup();
    };

    $scope.save = function () {
        $scope.extypeFormIsSubmitting = true;
        $http.post('/expense-type/save', {
            extype: $scope.editedExtype
        }).success(function (response) {
            $scope.tableform.$setPristine(true);
            if (response.errors != undefined) {
                $scope.extypeSubmitError = response.errors[0].join();
            } else {
                $scope.extypeSubmitError = '';
                var index = getIndexById(response.id);
                if (index > -1) {
                    $scope.extypes[index] = response;
                } else {
                    $scope.extypes.push(response);
                }
                initilizeEditedExtype();
                closePopup();
            }
            $scope.extypeFormIsSubmitting = false;


        }).error(function (data) {
            $scope.extypeFormIsSubmitting = false;
            $scope.extypeSubmitError      = data;
        });
    };

    $scope.hasExtypeSubmitError = function() {
        return $scope.extypeSubmitError != '';
    };

    $scope.setStatus = function(id, record_type) {
        $scope.editedExtype = $filter('filter')($scope.extypes, { id: id })[0];
        $scope.editedExtype.record_type = record_type;
        $scope.save();
    };

    var showPopup = function() {
        $scope.extypeSubmitError = '';
        $scope.tableform.$setPristine(true);
        $('#extype-popup-open').click();
    };

    var initilizeEditedExtype = function() {
        $scope.editedExtype = {
            id                   : 0,
            company_id           : companyId,
            name                 : '',
            limit_type           : '',
            record_type          : 'Active',
            groups               : $scope.groups,
            schedules            : $scope.schedules,
            limit_per_order      : '',
            company_user_group_id: '',
            soft_limit_max       : ''
        };
    };

    var closePopup = function() {
        $('.modal').click();
    };

    var getIndexById = function(id){
        return $scope.extypes.map(function(x) { return x.id; }).indexOf(id);
    };

});