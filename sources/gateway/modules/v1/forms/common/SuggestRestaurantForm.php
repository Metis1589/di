<?php
namespace gateway\modules\v1\forms\common;

use gateway\modules\v1\components\ApiResponse;
use gateway\modules\v1\forms\BaseRequestApiForm;

class SuggestRestaurantForm extends BaseRequestApiForm
{
    public $name;
    public $cuisine;
    public $area;
    public $phone;
    public $postcode;
    public $email;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return []; //todo
    }
}