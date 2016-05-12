<?php
namespace gateway\modules\v1\forms\common;

use gateway\modules\v1\forms\PaymentRequestApiForm;

class PaymentNotificationForm extends PaymentRequestApiForm
{
    
    public $live;
    public $eventCode;
    public $pspReference;
    public $originalReference;
    public $merchantReference;
    public $merchantAccountCode;
    public $eventDate;
    public $success;
    public $operations;
    public $reason;
    public $amount;
    public $value;


    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return []; //todo
    }
}