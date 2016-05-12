<?php
namespace gateway\modules\v1\forms\common;

use common\components\language\T;
use gateway\modules\v1\components\ApiResponse;
use gateway\modules\v1\forms\BaseRequestApiForm;

class SetVoucherForm extends BaseRequestApiForm
{
    public $code;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
//            ['code', 'required', 'message' => T::e('Code is missing')],
        ];
    }
}