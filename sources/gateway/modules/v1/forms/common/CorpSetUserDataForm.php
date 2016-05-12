<?php
namespace gateway\modules\v1\forms\common;

use gateway\modules\v1\forms\BaseRequestApiForm;

class CorpSetUserDataForm extends BaseRequestApiForm
{
    public $index;
    public $code_id;
    public $allocation;
    public $comment;

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