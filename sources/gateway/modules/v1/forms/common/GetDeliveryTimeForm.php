<?php
namespace gateway\modules\v1\forms\common;


use common\components\language\T;
use gateway\modules\v1\forms\BaseRequestApiForm;

class GetDeliveryTimeForm extends BaseRequestApiForm
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
            ['restaurant_id', 'required', 'message' => T::e('Restaurant ID is missing')],
            ['restaurant_id', 'gateway\modules\v1\components\validators\RestaurantIdValidator', 'message' => T::e('Invalid Restaurant ID'),'client_key'=>$this->client_key],
        ];
    }
}