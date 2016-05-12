<?php
namespace gateway\modules\v1\actions\common;

use common\enums\RecordType;
use common\models\User;
use gateway\modules\v1\forms\common\RequestPasswordResetForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;

class RequestPasswordResetAction extends PostApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return RequestPasswordResetForm
	 */
	protected function createRequestForm()
	{
		return new RequestPasswordResetForm();
	}

	/**
	 * reset user password.
	 *
	 * @param mixed $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            $user = User::findOne(['username' => $requestForm->username, 'record_type' => RecordType::Active]);
            $user->requestToResetPassword();

            return true;
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}