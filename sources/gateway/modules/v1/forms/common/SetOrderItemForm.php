<?php
namespace gateway\modules\v1\forms\common;

use gateway\modules\v1\components\ApiResponse;
use gateway\modules\v1\forms\BaseRequestApiForm;

class SetOrderItemForm extends BaseRequestApiForm
{
    public $restaurant_id;
    public $order_item_id;
    public $menu_item_id;
    public $quantity;
    public $selected_options;
    public $special_instructions;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return []; // todo
    }
}