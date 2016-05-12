<?php
namespace gateway\modules\v1\forms\common;
use gateway\modules\v1\forms\AdminRequestApiForm;

class UpdateOrderRefundForm extends AdminRequestApiForm
{
    /**
     * Additional form validation rules.
     *
     * @return array
     */
    public $order_id;
    public $client_refund;
    public $restaurant_refund;
    public $corporate_client_refund;
    public $corporate_restaurant_refund;
    public $restaurant_refund_diff;
    public $client_refund_diff;
    public $internal_comment;


    protected function customRules()
    {
        return []; // todo
    }
}