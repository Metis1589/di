<?php
namespace gateway\modules\v1\forms\common;

use gateway\modules\v1\components\ApiResponse;
use gateway\modules\v1\forms\BaseRequestApiForm;

class ReportGeneratePLForm extends BaseRequestApiForm
{
    public $date_from;
    public $date_to;
    public $restaurant_chain_id;
    public $restaurant_group_id;
    public $restaurant_id;

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