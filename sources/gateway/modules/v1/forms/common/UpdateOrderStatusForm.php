<?php
namespace gateway\modules\v1\forms\common;
use gateway\modules\v1\forms\AdminRequestApiForm;

class UpdateOrderStatusForm extends AdminRequestApiForm
{
    /**
     * Additional form validation rules.
     *
     * @return array
     */
    public $order_id;
    public $order_status;
    public $internal_comment;
    public $restaurant_comment;
    public $restaurant_charge;
    public $client_cost;
    public $client_received;
    public $restaurant_credit;
    public $ready_by;
    public $ready_by_time;
    public $cancellation_reason;


    protected function customRules()
    {
        return []; // todo
    }
}