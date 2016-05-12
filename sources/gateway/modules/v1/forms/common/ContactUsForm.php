<?php
namespace gateway\modules\v1\forms\common;

use common\components\language\T;
use gateway\modules\v1\forms\BaseRequestApiForm;

class ContactUsForm extends BaseRequestApiForm
{
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $order_number;
    public $message;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
            ['first_name', 'string',  'max' => 255, 'message' => T::e('Invalid first name')],
            ['last_name',  'string',  'max' => 255, 'message' => T::e('Invalid last name')],
            ['email',   'string',  'max' => 255, 'message' => T::e('Invalid Email')],
            ['email',   'email',   'message' => T::e('Invalid Email')],
            [['last_name', 'first_name'], 'required'],
            [['message', 'phone', 'order_number'],  'safe'],
        ];
    }
}