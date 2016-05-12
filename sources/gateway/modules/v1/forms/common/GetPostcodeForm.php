<?php
namespace gateway\modules\v1\forms\common;

use common\components\language\T;
use gateway\modules\v1\forms\BaseRequestApiForm;

class GetPostcodeForm extends BaseRequestApiForm
{
    public $postcode;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
            ['postcode', 'required', 'message' => T::e('Postcode is missing')],
        ];
    }
}