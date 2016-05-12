<?php
namespace gateway\modules\v1\forms\common;

use gateway\modules\v1\forms\BaseRequestApiForm;
use Yii;

class GetCustomFieldsForm extends BaseRequestApiForm
{

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
        ];
    }
}