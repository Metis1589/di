dineinApp.directive('numericOnly', function(){
    return {
        require: 'ngModel',
        link: function(scope, element, attrs, modelCtrl) {

            modelCtrl.$parsers.push(function (inputValue) {
                var transformedInput = inputValue.replace(/[^\d]/g, '');
                transformedInput = transformedInput.replace(/^0+/g, '');

                if (transformedInput != inputValue) {
                    modelCtrl.$setViewValue(transformedInput);
                    modelCtrl.$render();
                }

                return transformedInput;
            });
        }
    };
});

dineinApp.directive('numericTwoDecimals', function(){
    return {
        require: 'ngModel',
        link: function(scope, element, attrs, modelCtrl) {

            modelCtrl.$parsers.push(function (inputValue) {
                var transformedInput = inputValue.replace(/[^0-9\.$]/g, '');

                if (transformedInput != inputValue) {
                    modelCtrl.$setViewValue(transformedInput);
                    modelCtrl.$render();
                }

                return transformedInput;
            });
        }
    };
});

/**
 * Menu options controller
 */
dineinApp.controller('menuOptionsController', function ($scope, $http, $filter) {

    $scope.showCategoryType = function (option) {
        var selected = [];
        if (option.menu_option_category_type_id) {
            selected = $filter('filter')($scope.categoryTypes, {id: option.menu_option_category_type_id});
        }
        return selected.length ? selected[0].name : '';
    };

    // filter out Deleted elements
    $scope.filterOptions = function (option) {
        return option.record_type !== 'Deleted';
    };

    /**
     * Get maximum menu_option_id
     */
    $scope.getMaxOptionId = function() {
        var ids = [];
        if ($scope.options.length === 0){
            return 1;
        }

        for (var i = $scope.options.length; i--; ) {
            ids.push($scope.options[i].id);
        }
        return Math.max.apply(Math, ids);
    };

    /**
     * Get next available option id
     */
    $scope.getNextOptionId = function() {
        return $scope.getMaxOptionId() + 1;
    };

    /**
     * Get index by element
     */
    var getIndexByElement = function(option){
        if (option === null){
            return -1;
        }
        return getIndexById(option.id);
    };

    /**
     * Get index by id
     */
    var getIndexById = function(id){
        return $scope.options.map(function(x) {return x.id; }).indexOf(id);
    };

    /**
     * Delete option and recalculate following options
     */
    $scope.deleteOption = function (option) {
        var index = getIndexByElement(option);

        for (var i = index + 1; i < $scope.options.length; i++) {
            if ($scope.options[i].level > option.level){
                $scope.options[i].record_type = 'Deleted';
            }
            else {
                break;
            }
        }

        option.record_type = 'Deleted';

        // decrease sort order of the following elements in same level
        for (var i = index; i < $scope.options.length; i++) {
            if ($scope.options[i].level == option.level){
                $scope.options[i].sort_order--;
            }
            else if ($scope.options[i].level < option.level) {
                break;
            }
        }
    };

    /**
     * Add element to the end of the node
     */
    var addElement = function(option, is_category) {
        var index = getIndexByElement(option);
        var new_option = {
            id: $scope.getNextOptionId(),
            parent_id: option !== null ? option.id : null,
            level: option !== null ? option.level + 1 : 1,
            name_key: '',
            menu_option_category_type_id : is_category ? 1 : null,
            record_status: 'Active',
            is_new: true
        };

        var last_element = $scope.getLastElementInLevel(new_option.parent_id, new_option.level);
        new_option.sort_order = (last_element ? parseInt(last_element.sort_order) : 0) + 1;

        var current_level = option !== null ? option.level : 0;
        $scope.options.splice($scope.getLastIndex(current_level, index), 0, new_option);
    };

    /**
     * Add category
     */
    $scope.addCategory = function(option) {
        addElement(option, true);
    };

    /**
     * Add option
     */
    $scope.addOption = function(option) {
        addElement(option, false);
    };

    /**
     * Get last node element index
     */
    $scope.getLastIndex = function(level, index) {
        for (var i = index + 1; i < $scope.options.length; i++) {
            if ($scope.options[i].record_type == 'Deleted') {
                continue;
            }
            if ($scope.options[i].level <= level){
                return i;
            }
        }
        return $scope.options.length;
    };

    /**
     * Get last element in node on specified level
     */
    $scope.getLastElementInLevel = function(id, level) {
        var index = getIndexById(id);
        var last_element = null;
        for (var i = index + 1; i < $scope.options.length; i++) {
            if ($scope.options[i].record_type == 'Deleted') {
                continue;
            }
            if ($scope.options[i].level >= level){
                if ($scope.options[i].level == level){
                    last_element = $scope.options[i];
                }
            }
            else {
                break;
            }
        }

        return last_element;
    };

    /**
     * Move item up inside his level
     * @param option
     */
    $scope.up = function(option){
        var index = getIndexByElement(option);

        var moved_options = [option];

        var last_index = index;
        for (var i = index + 1; i < $scope.options.length; i++) {
            if ($scope.options[i].record_type == 'Deleted') {
                continue;
            }
            if ($scope.options[i].level > option.level){
                moved_options.push($scope.options[i]);
                last_index = i;
            }
            else {
                break;
            }
        }

        var insert_index = 0;
        for (var i = index; i--;) {
            if ($scope.options[i].record_type == 'Deleted') {
                continue;
            }
            if ($scope.options[i].level == option.level) {
                insert_index = i;
                break;
            }
        }

        // remove moved ite, with sub items
        $scope.options.splice(index, last_index - index + 1);

        // swap sort orders
        var option_sort_order = option.sort_order;
        option.sort_order = $scope.options[insert_index].sort_order;
        $scope.options[insert_index].sort_order = option_sort_order;

        // insert removed items at insert_index
        $scope.options.splice.apply($scope.options, [insert_index, 0].concat(moved_options));
    };

    /**
     * Move item down inside his level
     * @param option
     */
    $scope.down = function(option){

        var index = getIndexByElement(option);

        ids = [];
        ids.push(option);

        var last_index = index;
        for (var i = index + 1; i < $scope.options.length; i++) {
            if ($scope.options[i].record_type == 'Deleted') {
                continue;
            }
            if ($scope.options[i].level > option.level){
                ids.push($scope.options[i]);
                last_index = i;
            }
            else {
                break;
            }
        }

        $scope.options.splice(index, last_index - index + 1);

        last_index = $scope.getLastIndex($scope.options[index].level, index);

        // swap sort orders
        var option_sort_order = option.sort_order;
        option.sort_order = $scope.options[index].sort_order;
        $scope.options[index].sort_order = option_sort_order;

        // insert removed items at insert_index
        $scope.options.splice.apply($scope.options, [last_index, 0].concat(ids));
    };

    $scope.activate = function(option) {
        option.record_type = 'Active';
    };

    $scope.deactivate = function(option) {
        option.record_type = 'Inactive';
    };

    // cancel all changes
    $scope.cancel = function () {
        for (var i = $scope.options.length; i--; ) {
            var option = $scope.options[i];
            // undelete
            if (option.record_type === 'Deleted') {
                delete option;
            }
            // remove new
            if (option.is_new) {
                $scope.options.splice(i, 1);
            }
        }
    };

    $scope.loadTable = function() {
        $http.get('get-menu-options', {params: {id: menu_item_id}}).success(function(response) {
            $scope.options = response;
        });
    };

    /**
     * Save modified menu options
     */
    $scope.saveTable = function() {
        $scope.saving = true;
        $scope.save_error = false;

        $http.post('save-menu-options?id=' + menu_item_id, $scope.options).success(function(response) {
            $scope.saving = false;
            $scope.save_error = !response;
            if (response == false) {
                $scope.save_error = true;
            }
            else {
                $scope.loadTable();
            }
        });
    };

    $scope.$watch('tableform.$visible', function() {
        $scope.tableform.$show();
    });

    // data initialization
    var menu_item_id = $('#menu_item_id').val();

    $scope.categoryTypes = [
        {id: 1, name: 'Single'},
        {id: 2, name: 'Single or None'},
        {id: 3, name: 'Multiple - Single Option'},
        {id: 4, name: 'Multiple - Single Option or None'},
        {id: 5, name: 'Multiple - Multiple Option'},
        {id: 6, name: 'Multiple - Multiple Option or None'}
    ];

    $scope.loadTable();
});