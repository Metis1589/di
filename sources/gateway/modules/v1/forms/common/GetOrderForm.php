<?php
namespace gateway\modules\v1\forms\common;

use gateway\modules\v1\components\ApiResponse;
use gateway\modules\v1\forms\BaseRequestApiForm;
use common\components\language\T;

class GetOrderForm extends BaseRequestApiForm
{
    public $restaurant_id;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
//            ['restaurant_id', 'required', 'message' => T::e('Restaurant ID is missing')],
            ['restaurant_id', 'gateway\modules\v1\components\validators\RestaurantIdValidator', 'message' => T::e('Invalid Restaurant ID'),'client_key'=>$this->client_key],
        ];
    }
}