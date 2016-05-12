<?php
namespace gateway\modules\v1\forms\common;

use common\components\language\T;
use gateway\modules\v1\components\ApiResponse;
use gateway\modules\v1\forms\BaseRequestApiForm;

class GetOrderStatusForm extends BaseRequestApiForm
{
    public $order_number;
    public $clear_order;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
            ['order_number', 'required', 'message' => T::e('Order is missing')],
            ['order_number', 'integer', 'message' => T::e('Invalid Order number')],
            ['clear_order', 'safe'],
        ];
    }
}