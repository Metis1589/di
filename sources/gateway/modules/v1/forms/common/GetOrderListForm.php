<?php
namespace gateway\modules\v1\forms\common;

use gateway\modules\v1\forms\AdminRequestApiForm;
use gateway\modules\v1\forms\BaseRequestApiForm;

class GetOrderListForm extends BaseRequestApiForm
{
    public $client_key;
    public $custom_fields;
    public $filter_statuses;
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