<?php
namespace gateway\modules\v1\actions\common;

use gateway\modules\v1\forms\common\GetAddressesForm;
use gateway\modules\v1\components\GetApiAction;
use Exception;
use Yii;

class GetUserAddressesAction extends GetApiAction
{
	/**
	 * user addresses.
	 *
	 * @param mixed $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            $session_user = Yii::$app->userCache->getUser();

            return $session_user->addresses;
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}