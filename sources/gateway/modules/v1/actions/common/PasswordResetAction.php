<?php
namespace gateway\modules\v1\actions\common;

use gateway\modules\v1\components\PostApiAction;
use Exception;
use gateway\modules\v1\forms\common\PasswordResetForm;
use Yii;

class PasswordResetAction extends PostApiAction
{
    /**
     * Creates request form used to validate request parameters.
     *
     * @return PasswordResetForm
     */
    protected function createRequestForm()
    {
        return new PasswordResetForm();
    }

    /**
     * Reset Password.
     *
     * @param PasswordResetForm $requestForm Request form class instance.
     *
     * @return string|boolean
     */
    protected function getResponseData($requestForm)
    {
        try {
            $requestForm->user->password = $requestForm->user->generatePassword($requestForm->password);
            return $requestForm->user->activate();
        }
        catch (Exception $ex) {
            return $ex;
        }
    }
}