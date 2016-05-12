<?php
namespace gateway\modules\v1\forms\common;

use common\components\language\T;
use gateway\modules\v1\forms\BaseRequestApiForm;
use Yii;

class AddReviewForm extends BaseRequestApiForm
{
    public $restaurant_id;
    public $order_number;
    public $rating;
    public $title;
    public $text;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
            ['restaurant_id', 'required', 'message' => T::e('Restaurant ID is missing')],
            ['restaurant_id', 'gateway\modules\v1\components\validators\RestaurantIdValidator', 'message' => T::e('Invalid Restaurant ID'), 'client_key'=>$this->client_key],
            ['order_number', 'required', 'message' => T::e('Order is missing')],
            ['order_number', 'integer', 'message' => T::e('Invalid Order number')],
            ['rating', 'required', 'message' => T::e('Rating is missing')],
          //  ['rating', 'integer', 'message' => T::e('Rating is invalid')],
            ['title', 'required', 'message' => T::e('Title is missing')],
            ['text', 'required', 'message' => T::e('Review is missing')],
        ];
    }
}