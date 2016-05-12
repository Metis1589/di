<?php
namespace gateway\modules\v1\forms\common;

use gateway\modules\v1\forms\AdminRequestApiForm;

class GetInternalOrderForm extends AdminRequestApiForm
{
    public $order_id;
    
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