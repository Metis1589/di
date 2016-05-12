<?php
namespace gateway\modules\v1\actions\inntouch;

use common\enums\OrderStatus;
use gateway\modules\v1\components\inntouch\InnTouchApiAction;
use Exception;
use gateway\modules\v1\forms\common\ActivateAccountForm;
use gateway\modules\v1\forms\inntouch\InnTouchOrderCompleteForm;
use Yii;

class InnTouchOrderCompleteAction extends InnTouchApiAction
{
    /**
     * Creates request form used to validate request parameters.
     *
     * @return ActivateAccountForm
     */
    protected function createRequestForm()
    {
        return new InnTouchOrderCompleteForm();
    }

    /**
     * Activate Account.
     *
     * @param InnTouchOrderCompleteForm $requestForm Request form class instance.
     *
     * @return string|boolean
     */
    protected function getResponseData($requestForm)
    {
        try {
            $requestForm->order->status = OrderStatus::PaymentReceived;
            if (!$requestForm->order->save()) {
                throw new Exception('Error saving order');
            }
            return [];
        }
        catch (Exception $ex) {
            return $ex;
        }
    }
}