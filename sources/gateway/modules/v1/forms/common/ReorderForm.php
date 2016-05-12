<?php
namespace gateway\modules\v1\forms\common;

use common\components\language\T;
use gateway\modules\v1\components\ApiResponse;
use gateway\modules\v1\forms\BaseRequestApiForm;

class ReorderForm extends BaseRequestApiForm
{
    public $order_id;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
            ['order_id', 'required', 'message' => T::e('Order is missing')],
            ['order_id', 'integer', 'message' => T::e('Invalid Order number')],
        ];
    }
}