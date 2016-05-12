<?php
namespace gateway\modules\v1\forms\common;


use common\components\language\T;
use gateway\modules\v1\forms\BaseRequestApiForm;

class RestaurantsSearchForm extends BaseRequestApiForm
{
    public $postcode;
    public $delivery_type;
    public $later_date;
    public $later_time;
    public $seo_area_id;
    public $cuisine_id;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
//            ['postcode', 'required', 'message' => T::e('Postcode is missing')],
            ['delivery_type', 'required', 'message' => T::e('Delivery Type is missing')],
            ['delivery_type', 'gateway\modules\v1\components\validators\DeliveryTypeValidator', 'message' => T::e('Invalid Delivery Type')],
//            ['later_date', 'required', 'message' => T::e('Later Date is missing')],
            ['later_date', 'gateway\modules\v1\components\validators\LaterDateValidator', 'message' => T::e('Invalid Later Date')],
//            ['later_time', 'required', 'message' => T::e('Later Time is missing')],
            ['later_time', 'gateway\modules\v1\components\validators\LaterTimeValidator', 'message' => T::e('Invalid Later Time')],
            ['seo_area_id', 'integer', 'message' => T::e('Invalid Area ID')],
            ['cuisine_id', 'integer', 'message' => T::e('Invalid Cuisine ID')],
        ];
    }
}