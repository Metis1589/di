<?php
namespace gateway\modules\v1\actions\common;

use gateway\modules\v1\components\PostApiAction;
use Exception;
use gateway\modules\v1\forms\common\ActivateAccountForm;
use Yii;

class ActivateAccountAction extends PostApiAction
{
    /**
     * Creates request form used to validate request parameters.
     *
     * @return ActivateAccountForm
     */
    protected function createRequestForm()
    {
        return new ActivateAccountForm();
    }

    /**
     * Activate Account.
     *
     * @param ActivateAccountForm $requestForm Request form class instance.
     *
     * @return string|boolean
     */
    protected function getResponseData($requestForm)
    {
        try {
            return $requestForm->user->activate();
        }
        catch (Exception $ex) {
            return $ex;
        }
    }
}