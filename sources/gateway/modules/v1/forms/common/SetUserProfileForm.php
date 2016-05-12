<?php
namespace gateway\modules\v1\forms\common;

use gateway\modules\v1\components\ApiResponse;
use gateway\modules\v1\forms\BaseRequestApiForm;

class SetUserProfileForm extends BaseRequestApiForm
{
    public $first_name;
    public $last_name;
    public $email;
    public $password;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [];  // todo
    }
}