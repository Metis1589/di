<?php
namespace gateway\modules\v1\actions\inntouch;

use gateway\modules\v1\components\inntouch\InnTouchApiAction;
use Exception;
use gateway\modules\v1\forms\common\ActivateAccountForm;
use gateway\modules\v1\forms\inntouch\InnTouchOrderGetForm;
use gateway\modules\v1\models\InnTouchOrderGet;
use Yii;

class InnTouchOrderGetAction extends InnTouchApiAction
{
    /**
     * Creates request form used to validate request parameters.
     *
     * @return ActivateAccountForm
     */
    protected function createRequestForm()
    {
        return new InnTouchOrderGetForm();
    }

    /**
     * Activate Account.
     *
     * @param InnTouchOrderGetForm $requestForm Request form class instance.
     *
     * @return string|boolean
     */
    protected function getResponseData($requestForm)
    {
        try {
            $intouchOrder = InnTouchOrderGet::create($requestForm->order);
            return $intouchOrder;
        }
        catch (Exception $ex) {
            return $ex;
        }
    }
}