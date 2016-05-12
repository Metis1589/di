dineinApp.directive('menuOption', function($compile, $filter, orderService, recursionHelper) {
    return {
        restrict: 'E',
        template: '<div id="option-{{option.id}}" ng-repeat="option in options" repeat-end="repeatComplete()" >' +
                '<div class="select_set menu_item_size menu_item_toppings" ng-class="{\'invalid\' : !isMenuOptionCategoryValid(option) }" ng-if="option.is_category">' +
                    '<span class="pseudo_input" ng-if="option.menu_option_category_type_id == 1 || option.menu_option_category_type_id == 2">{{getSingleMenuItem(option.id) != null ? getSingleMenuItem(option.id).name_key : option.name_key  | cut:true:20}}</span>' +
                    '<span class="pseudo_input" ng-if="option.menu_option_category_type_id > 2">{{option.name_key}}</span>' +
                    '<input type="text">' +
                    '<p class="description" ng-show="option.description_key">{{option.description_key}}</p>' +
                    '<ul ng-if="option.is_last_category">' +
                        '<!-- Single/Single Or None !-->' +
                        '<li data-title="{{o.name_key}}" data-value="{{o.id}}" ng-repeat="o in option.options" ng-click="setSingleMenuItem(o)" ng-if="option.menu_option_category_type_id == 1 || option.menu_option_category_type_id == 2">' +
                            '<p>{{o.name_key | cut:true:20}}</p>' +
                            '<span class="price" ng-if="o.web_price">{{priceSymbol+o.web_price}}</span>' +
                        '</li>' +
                        '<!-- Multiple - Single Option/Multiple - Single Option or None !-->' +
                        '<li data-title="{{o.name_key}}" data-value="{{o.id}}" ng-repeat="o in option.options" ng-if="option.menu_option_category_type_id == 3 || option.menu_option_category_type_id == 4">' +
                            '<input type="checkbox" id="ch_{{o.id}}" ng-checked="isOptionSelected(option.id, o.id)">' +
                            '<label ng-click="setMenuOptionByCheckbox(option, o)">{{o.name_key | cut:true:20}}<span ng-show="o.web_price"> - {{priceSymbol+o.web_price}}</span></label>' +
                        '</li>' +

                        '<!-- Multiple - Multiple Option/Multiple - Multiple Option or None !-->' +
                        '<li data-title="{{o.name_key}}" data-value="{{o.id}}" ng-repeat="o in option.options" ng-if="option.menu_option_category_type_id == 5 || option.menu_option_category_type_id == 6">' +
                            '<p>{{o.name_key | cut:true:20}}</p>' +
                            '<span class="item-quantity">{{countSelectedOptions(o)}}</span>' +
                            '<div class="item-controls">' +
                                '<i class="plus" ng-click="setMenuOption(o)" ng-show="canAddOptionsInCategory(option)"></i>' +
                                '<i class="minus" ng-click="removeMenuOption(o)" ng-show="countSelectedOptions(o) > 0"></i>' +
                            '</div>' +
                        '</li>' +
                    '</ul>' +
                    '<menu-option options="option.options" item="item" price-symbol="{{priceSymbol}}"></menu-option>' +
                '</div>' +
                '<!-- Selected Options for Multiple - Single Option/Multiple - Single Option or None !-->' +
                '<div class="selected_options" ng-show="selectedOptions(option.id).length" ng-if="option.menu_option_category_type_id == 3 || option.menu_option_category_type_id == 4">' +
                    '<div class="menu_option_selected" ng-repeat="selectedOption in selectedOptions(option.id)">' +
                        '{{selectedOption.option.name_key | cut:true:15}}<i class="checked_block_carret" ng-click="setMenuOptionByCheckbox(option, selectedOption.option)"></i>' +
                    '</div>' +
                '</div>' +
                '<!-- Selected Options for Multiple - Multiple Option/Multiple - Multiple Option or None !-->' +
                '<div class="selected_options" ng-show="selectedOptions(option.id).length" ng-if="option.menu_option_category_type_id == 5 || option.menu_option_category_type_id == 6">' +
                    '<div class="menu_option_selected" ng-repeat="selectedOption in selectedOptions(option.id)">' +
                        '{{selectedOption.quantity}} x {{selectedOption.option.name_key | cut:true:10}}<i class="checked_block_carret" ng-click="setMenuOptionByCheckbox(option, selectedOption.option)"></i>' +
                    '</div>' +
                '</div>' +
            '</div>',
        scope: {
            options    : "=options",
            item       : "=item",
            priceSymbol: "@"
        },
        compile: function(element) {
            // Use the compile function from the recursionHelper,
            // And return the linking function(s) which it returns
            var link = function($scope, $element) {
                $($element).hide();

                $scope.getSingleMenuItem = function(menuOptionCategoryId) {
                    var selectedOptionInCategory = orderService.getSelectedOptionsInMenuCategory($scope.item.selected_options, menuOptionCategoryId);
                    if (selectedOptionInCategory != null && selectedOptionInCategory.length > 0) {
                        return selectedOptionInCategory[0].option;
                    }
                    return null;
                };

                $scope.setSingleMenuItem = function(option) {

                    var selectedOptionInCategory = $scope.getSingleMenuItem(option.parent_id);

                    if (selectedOptionInCategory != null) {
                        orderService.removeMenuOption($scope.item, selectedOptionInCategory);
                    }
                    orderService.setMenuOption($scope.item, option);
                    $('#option-' + option.parent_id).find('.select_set_carret').click();
                };

                $scope.isOptionSelected = function(menuOptionCategoryId, optionId) {
                    var selectedOptionsInCategory = orderService.getSelectedOptionsInMenuCategory($scope.item.selected_options, menuOptionCategoryId);
                    if (selectedOptionsInCategory != null) {
                        for (var i = 0; i < selectedOptionsInCategory.length; i++) {
                            if (selectedOptionsInCategory[i].option.id == optionId) {
                                return true;
                            }
                        }
                    }
                    return false;
                };

                $scope.selectedOptions = function(menuOptionCategoryId) {
                    return orderService.getSelectedOptionsInMenuCategory($scope.item.selected_options, menuOptionCategoryId);
                };

                $scope.setMenuOptionByCheckbox = function(menuOptionCategory, option) {
                    if (!$scope.isOptionSelected(menuOptionCategory.id, option.id)) {
                        if (!$scope.canAddOptionsInCategory(menuOptionCategory)) {
                            return;
                        }
                        orderService.setMenuOption($scope.item, option);
                        $('#ch_' + option.id).prop('checked', true);
                    } else {
                        console.log(2);
                        orderService.removeMenuOption($scope.item, option);
                        $('#ch_' + option.id).prop('checked', false);
                    }
                };

                $scope.setMenuOption = function(option) {
                    orderService.setMenuOption($scope.item, option);
                };

                $scope.removeMenuOption = function(option) {
                    orderService.removeMenuOption($scope.item, option);
                };

                $scope.countSelectedOptions = function(option) {
                    var selectedOption = orderService.getSelectedOptionByMenuOption($scope.item, option);

                    if (selectedOption == null) {
                        return 0;
                    }

                    return selectedOption.quantity;
                };

                $scope.canAddOptionsInCategory = function(category) {

                    if (category.max_category_items == undefined || category.max_category_items == null || category.max_category_items == '') {
                        return true;
                    }
                    var selectedOptionsCount = 0;
                    for(var i = 0; i < category.options.length; i++) {
                        selectedOptionsCount += $scope.countSelectedOptions(category.options[i]);
                    }
                    return selectedOptionsCount < parseInt(category.max_category_items);
                };

                //set default options
                if ($scope.options != undefined) {

                    for (var i = 0; i < $scope.options.length; i++) {
                        var category = $scope.options[i];

                        if (category.is_last_category) {

                            for (var j = 0; j < category.options.length; j++) {
                                var option = category.options[j];
                                if (option.is_default == '1' && !$scope.isOptionSelected(category.id, option.id)) {
                                    if (category.menu_option_category_type_id == 1 || category.menu_option_category_type_id == 2) {
                                        $scope.setSingleMenuItem(option);
                                    } else if (category.menu_option_category_type_id == 3 || category.menu_option_category_type_id == 4) {
                                        $scope.setMenuOptionByCheckbox(category.id, option);
                                    } else {
                                        $scope.setMenuOption(option);
                                    }
                                }
                            }
                        }
                    }
                }

                $scope.repeatComplete = function() {

                    $($element).show();

                    $(element).find('.menu_item_size').dineinSelect({
                        timeInterval: 1,
                        frameHeight: 200,
                        onlyOne: false
                    });
                };

                $scope.isMenuOptionCategoryValid = function(menuOptionCategory) {
                    return orderService.isMenuOptionCategoryValid($scope.item, menuOptionCategory);


                };
            };
            return recursionHelper.compile(element, link);
        }
    };
});