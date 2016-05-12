<?php
namespace gateway\modules\v1\forms\common;

use common\components\language\T;
use gateway\modules\v1\components\ApiResponse;
use gateway\modules\v1\forms\BaseRequestApiForm;

class RegisterForm extends BaseRequestApiForm
{
    public $title;
    public $first_name;
    public $last_name;
    public $address1;
    public $address2;
    public $city;
    public $postcode;
    public $phone;
    public $username;
    public $password;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
            ['username',  'common\validators\CustomUniqueValidator', 'targetClass' =>'common\models\User', 'targetAttribute' => 'username', 'message' => T::e('Email is already in use')],
            ['password',  'required', 'message' => T::e('Password is missing')],
            ['password',  'string',   'max' => 255, 'message' => T::e('Invalid Password')],
            ['username',  'required', 'message' => T::e('Email is missing')],
            ['username',  'string',   'max' => 255, 'message' => T::e('Invalid Email')],
            ['username',  'email',    'message' => T::e('Invalid Email')],
            ['phone',     'string',   'max' => 50, 'message' => T::e('Invalid Phone')],
            ['postcode',  'string',   'max' => 45, 'message' => T::e('Invalid Postcode')],
            ['postcode',  'required', 'message' => T::e('Postcode is missing')],
            ['city',      'string',   'max' => 255, 'message' => T::e('Invalid City')],
            ['city',      'required', 'message' => T::e('City is missing')],
            ['address2',  'string',   'max' => 50, 'message' => T::e('Invalid Address 2')],
            ['address1',  'string',   'max' => 50, 'message' => T::e('Invalid Address 1')],
            ['address1',  'required', 'message' => T::e('Address 1 is missing')],
            ['first_name', 'string',  'max' => 255, 'message' => T::e('Invalid first name')],
            ['last_name',  'string',  'max' => 255, 'message' => T::e('Invalid last name')],
            ['title',      'string',  'max' => 45, 'message' => T::e('Invalid title')],
        ];
    }
}