<?php
namespace gateway\modules\v1\forms\common;

use common\components\language\T;
use gateway\modules\v1\forms\BaseRequestApiForm;

class CorpRemoveUserForm extends BaseRequestApiForm
{
    public $index;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
            ['index', 'required', 'message' => T::e('Index is missing')],
            ['index', 'integer', 'message' => T::e('Invalid Index')],
        ];
    }
}