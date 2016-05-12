<?php
namespace gateway\modules\v1\forms\common;

use common\components\language\T;
use gateway\modules\v1\components\ApiResponse;
use gateway\modules\v1\forms\BaseRequestApiForm;

class CheckoutForm extends BaseRequestApiForm
{
    public $additional_requirements;
    public $include_utensils;
    public $delivery_address;
    public $billing_address;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
//            ['include_utensils', 'required', 'message' => T::e('include utensils is missing')],
        ];
    }
}