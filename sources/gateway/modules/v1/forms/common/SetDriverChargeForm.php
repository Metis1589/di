<?php
namespace gateway\modules\v1\forms\common;

use common\components\language\T;
use gateway\modules\v1\components\ApiResponse;
use gateway\modules\v1\forms\BaseRequestApiForm;

class SetDriverChargeForm extends BaseRequestApiForm
{
    public $driver_charge;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
            ['driver_charge', 'required', 'message' => T::e('Driver charge is missing')],
            ['driver_charge', 'number', 'message' => T::e('Driver charge is invalid')],
            // todo validate if positive
        ];
    }
}