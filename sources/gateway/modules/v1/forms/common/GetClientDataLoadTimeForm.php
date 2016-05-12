<?php
namespace gateway\modules\v1\forms\common;

use gateway\modules\v1\components\ApiResponse;
use gateway\modules\v1\forms\BaseRequestApiForm;

class GetClientDataLoadTimeForm extends BaseRequestApiForm
{
    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [];
    }
}