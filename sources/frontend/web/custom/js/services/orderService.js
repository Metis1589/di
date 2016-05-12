'use strict';

/**
 * Service performing order related requests and calculations
 */
dineinApp.service('orderService', function(apiService, $filter) {

    /**
     * default cart
     * @type Array
     * @private
     */
    var _cart = {
        items: [],
        delivery_charge: 0,
        total: 0,
        discount_total: 0,
        voucher_code: null,
        driver_charge: null,
        is_processing: true,
        is_valid: true,
        validate_error: null
    };

    /**
     * Get cart
     * @returns {Array}
     */
    this.getCart = function() {
        return _cart;
    };

    /**
     * Generate next order item id
     * @returns {*}
     */
    var getNextOrderItemId = function() {
        var ids = [];
        if (_cart.items.length === 0){
            return 1;
        }

        return _cart.items[_cart.items.length - 1].id + 1;
    };

    /**
     * find order item by menu_item_id
     * @param menu_item_id
     * @returns {*}
     */
    var getOrderItemIdByMenuItemId = function(menu_item_id) {
        for (var i = 0; i < _cart.items.length; i++) {
            if (parseInt(_cart.items[i].menu_item_id) == parseInt(menu_item_id)) {
                return _cart.items[i].id;
            }
        }

        return null;
    };

    /**
     * get order item index by order_item_id
     * @param order_item_id
     * @returns {number}
     */
    var getIndexByOrderItemId = function(order_item_id) {
        for (var i = 0; i < _cart.items.length; i++) {
            if (parseInt(_cart.items[i].id) == parseInt(order_item_id)) {
                return i;
            }
        }

        return -1;
    };

    /**
     * calculate order total
     */
    var calculateTotal = function() {
        _cart.total = _cart.delivery_charge - _cart.discount_total + _cart.driver_charge;
        for (var i = 0; i < _cart.items.length; i++) {
            _cart.total += _cart.items[i].price * _cart.items[i].quantity;
            if(_cart.items[i].selected_options && 'length' in _cart.items[i].selected_options){
                for (var j = 0; j < _cart.items[i].selected_options.length; j++) {
                    _cart.total += _cart.items[i].selected_options[j].option.price * _cart.items[i].selected_options[j].quantity;
                }
            }
        }
    };

    this.getTotal = function() {
        return _cart.total;
    };

    var updateCartFromData = function(data) {
        _cart.items = data.items;
        _cart.delivery_charge = data.delivery_charge;
        _cart.discount_total = data.discount_total;
        _cart.voucher_code = data.voucher_code;
        _cart.driver_charge = data.driver_charge;

        _cart.is_valid = data.is_valid;
        _cart.validate_error = data.validate_error;

        _cart.is_processing = false;
    };

    /**
     * Set order item
     * @param restaurant_id
     * @param order_item_id
     * @param menu_item_id
     * @param has_options
     * @param quantity
     * @param price
     * @param name
     * @param selected_options
     */
    this.setOrderItem = function(restaurant_id, order_item_id, menu_item_id, has_options, quantity, price, name, selected_options, special_instructions) {

        if (order_item_id == null) {

            if (!has_options) {
                order_item_id = getOrderItemIdByMenuItemId(menu_item_id);
            }

            if (order_item_id == null) {
                order_item_id = getNextOrderItemId();

                var item = {
                    id: order_item_id,
                    menu_item_id: menu_item_id,
                    name: name,
                    price: price,
                    web_price: price,
                    quantity: 0,
                    selected_options: selected_options
                };

                _cart.items.push(item);
            }
        }

        var index = getIndexByOrderItemId(order_item_id);

        item = _cart.items[index];

        item.selected_options = selected_options;

        item.quantity = quantity == null ? parseInt(item.quantity) + 1 : quantity;

        calculateTotal();

        var deferred = apiService.getDeferred();

        _cart.is_processing = true;

        apiService.post('set-order-item', {
            restaurant_id: restaurant_id,
            order_item_id: order_item_id,
            menu_item_id: menu_item_id,
            quantity: item.quantity,
            selected_options: item.selected_options,
            special_instructions: special_instructions
        })
            .success(function (data) {
                updateCartFromData(data);

                calculateTotal();

                deferred.resolve(data);
            })
            .error(function (status_code, error_message) {
                deferred.reject({status_code: status_code, error_message: error_message});
            });

        return deferred.promise;
    };

    this.isMenuItemValid = function(menuItem) {
        if (menuItem == null) {
            return false;
        }
        for(var i = 0; i < menuItem.options.length; i++) {
            if (!this.isMenuOptionCategoryValid(menuItem, menuItem.options[i])) {
                return false;
            }
        }
        return true;
    }

    this.isMenuOptionCategoryValid = function(menuItem, menuOptionCategory) {
        if (menuOptionCategory.is_last_category) {
            switch (menuOptionCategory.menu_option_category_type_id) {
                case '1':
                    return this.isSingleMenuOptionCategoryValid(menuItem, menuOptionCategory);
                case '2':
                    return this.isSingleOrNoneMenuOptionCategoryValid(menuItem, menuOptionCategory);
                case '3':
                    return this.isMultipleMenuOptionCategoryValid(menuItem, menuOptionCategory);
                case '4':
                    return this.isMultipleOrNoneMenuOptionCategoryValid(menuItem, menuOptionCategory);
                case '5':
                    return this.isMultipleMenuOptionCategoryValid(menuItem, menuOptionCategory);
                case '6':
                    return this.isMultipleOrNoneMenuOptionCategoryValid(menuItem, menuOptionCategory);
            }
        } else {
            for(var i = 0; i < menuOptionCategory.options.length; i++) {
                if (!this.isMenuOptionCategoryValid(menuItem, menuOptionCategory.options[i])) {
                    return false;
                }
            }
            return true;
        }

    }

    this.isSingleMenuOptionCategoryValid = function(menuItem, menuOptionCategory) {
        if (!this.hasMenuItemSelectedOptions(menuItem)) {
            return false;
        }
        var selectedOptionsCount = (this.getSelectedOptionsInMenuCategory(menuItem.selected_options,menuOptionCategory.id)).length;
        return (selectedOptionsCount == 1);
    }

    this.isSingleOrNoneMenuOptionCategoryValid = function(menuItem, menuOptionCategory) {
        if (!this.hasMenuItemSelectedOptions(menuItem)) {
            return true;
        }
        var selectedOptionsCount = (this.getSelectedOptionsInMenuCategory(menuItem.selected_options,menuOptionCategory.id)).length;
        return (selectedOptionsCount <= 1);
    }

    this.isMultipleMenuOptionCategoryValid = function(menuItem, menuOptionCategory) {
        if (!this.hasMenuItemSelectedOptions(menuItem)) {
            return false;
        }
        var selectedOptionsCount = (this.getSelectedOptionsInMenuCategory(menuItem.selected_options, menuOptionCategory.id)).length;
        return (selectedOptionsCount > 0);
    }

    this.isMultipleOrNoneMenuOptionCategoryValid = function(menuItem, menuOptionCategory) {
        if (!this.hasMenuItemSelectedOptions(menuItem)) {
            return true;
        }
        var selectedOptionsCount = (this.getSelectedOptionsInMenuCategory(menuItem.selected_options,menuOptionCategory.id)).length;
        return (selectedOptionsCount >= 0);
    }

    this.getSelectedOptionsInMenuCategory = function(selectedOptions, menuOptionCategoryId) {
        if (selectedOptions == undefined || selectedOptions == null) {
            return null;
        }

        return $filter('filter')(selectedOptions, function(option) {
            return option.option.parent_id == menuOptionCategoryId
        });
    }

    this.hasMenuItemSelectedOptions = function(menuItem) {
        return (menuItem.selected_options != undefined && menuItem.selected_options != null && menuItem.selected_options.length > 0);
    }

    this.setMenuOption = function(menuItem, menuOption) {
        if (menuItem.selected_options == null) {
            menuItem.selected_options = [];
        }

        var selectedOption = this.getSelectedOptionByMenuOption(menuItem, menuOption);

        if (selectedOption != null) {
            selectedOption.quantity++;
        }
        else {
            selectedOption = {option: menuOption, quantity: 1};
            menuItem.selected_options.push(selectedOption);
        }
    };

    this.removeMenuOption = function(menuItem, menuOption) {
        var selectedOption = this.getSelectedOptionByMenuOption(menuItem, menuOption);
        if (selectedOption) {
            selectedOption.quantity--;
        }
        if (selectedOption.quantity == 0) {
            var index = menuItem.selected_options.indexOf(selectedOption);
            menuItem.selected_options.splice(index, 1);
        }
    }

    this.getSelectedOptionByMenuOption = function(menuItem, menuOption) {
        var selectedOption = null;

        if (menuItem.selected_options != undefined && menuItem.selected_options != null) {
            for (var i = 0; i < menuItem.selected_options.length; i++) {

                if (menuItem.selected_options[i].option.id == menuOption.id) {
                    selectedOption = menuItem.selected_options[i];
                    break;
                }
            }
        }


        return selectedOption;
    }

    this.getItemPriceWithOptions = function(item) {
        if (item == null) {
            return 0;
        }
        var result = parseFloat(item.web_price);
        if (item.selected_options != null) {
            for(var i = 0; i < item.selected_options.length; i++) {
                if (item.selected_options[i].option.web_price != null) {
                    result += parseFloat(item.selected_options[i].option.web_price) * item.selected_options[i].quantity;
                }
            }
        }
        result *= item.quantity;
        return parseFloat(result).toFixed(2);
    }

    /**
     * get order
     * @param restaurant_id
     * @returns {promise.promise|jQuery.promise|d.promise|promise|jQuery.ready.promise}
     */
    this.getOrder = function(restaurant_id) {

        _cart.is_processing = true;

        var deferred = apiService.getDeferred();

        apiService.get('get-order', {
            restaurant_id: restaurant_id
        })
        .success(function (data) {
            updateCartFromData(data);

            calculateTotal();

            deferred.resolve(data);
        })
        .error(function (status_code, error_message) {
            deferred.reject({status_code: status_code, error_message: error_message});
        });

        return deferred.promise;
    };

    /**
     * checkout action
     * @param additional_requirements
     * @param include_utensils
     * @param delivery_address
     * @param billing_address
     * @returns {*}
     */
    this.checkout = function(additional_requirements, include_utensils, delivery_address, billing_address) {
        return apiService.post('checkout', {
            additional_requirements: additional_requirements,
            include_utensils: include_utensils,
            delivery_address: delivery_address,
            billing_address: billing_address
        });
    };

    /**
     * set voucher code
     * @param code
     * @returns {*}
     */
    this.setVoucher = function(code) {
        var deferred = apiService.getDeferred();

        _cart.is_processing = true;

        apiService.post('set-voucher',
            {
                code: code
            })
            .success(function (data) {
                //_cart.discount = data;
                updateCartFromData(data);

                calculateTotal();

                deferred.resolve(data);
            })
            .error(function (status_code, error_message) {
                _cart.discount_total = 0;
                _cart.voucher_code = null;

                calculateTotal();
                deferred.reject({status_code: status_code, error_message: error_message});
            });

        return deferred.promise;
    };

    /**
     * set driver charge
     * @param driver_charge
     */
    this.setDriverCharge = function(driver_charge) {
        _cart.driver_charge = driver_charge;
        calculateTotal();
        return apiService.post('set-driver-charge',
            {
                driver_charge: driver_charge
            });
    };

    /**
     * change postcode
     * @param postcode
     */
    this.changePostcode = function(postcode) {

    };

    /**
     * reorder previous order
     * @param order_id
     * @returns {*}
     */
    this.reorder = function(order_id) {
        return apiService.post('reorder',
            {
                order_id: order_id
            });
    };

    /**
     * Save payment
     * @param params
     * @returns {*}
     */
    this.savePayment = function(params) {
        return apiService.post('save-payment',
            {
                auth_result: params.auth_result,
                psp_reference: params.psp_reference,
                merchant_reference: params.merchant_reference,
                skin_code: params.skin_code,
                payment_method: params.payment_method,
                merchant_sig: params.merchant_sig
            });
    };

    /**
     * get order status
     * @param order_number
     * @returns {*}
     */
    this.getOrderStatus = function(order_number, clear_order) {
        return apiService.get('get-order-status',
            {
                order_number: order_number,
                clear_order: clear_order
            });
    };

    /**
     * Set delivery charge
     * @param delivery_charge
     */
    this.setDeliveryCharge = function(delivery_charge){
        _cart.delivery_charge = delivery_charge;
    }
});
