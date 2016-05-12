<?php
namespace gateway\modules\v1\forms\common;
use gateway\modules\v1\forms\BaseRequestApiForm;

class UpdateOrderItemDisplayForm extends BaseRequestApiForm
{
    /**
     * Additional form validation rules.
     *
     * @return array
     */
    public $order_item_id;
    public $display_index;

    protected function customRules()
    {
        return []; // todo
    }
}