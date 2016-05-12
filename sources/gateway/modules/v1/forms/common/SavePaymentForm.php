<?php
namespace gateway\modules\v1\forms\common;

use gateway\modules\v1\forms\BaseRequestApiForm;


class SavePaymentForm extends BaseRequestApiForm
{
    
    public $auth_result;
    public $psp_reference;
    public $merchant_reference;
    public $skin_code;
    public $payment_method;
    public $merchant_sig;


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