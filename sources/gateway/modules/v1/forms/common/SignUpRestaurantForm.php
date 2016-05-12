<?php
namespace gateway\modules\v1\forms\common;

use gateway\modules\v1\components\ApiResponse;
use gateway\modules\v1\forms\BaseRequestApiForm;

class SignUpRestaurantForm extends BaseRequestApiForm
{
    public $restaurant_name;
    public $restaurant_address1;
    public $restaurant_address2;
    public $restaurant_city;
    public $restaurant_postcode;
    public $restaurant_phone;
    public $cuisine_1;
    public $cuisine_2;
    public $cuisine_3;
    public $offer_delivery;
    public $takeaway_service;
    public $takeaways_count;
    
    public $first_name;
    public $last_name;
    public $role;
    public $email;
    public $contact_phone;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return []; // todo
    }
}