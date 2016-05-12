<?php
namespace gateway\modules\v1\actions\common;

use gateway\modules\v1\forms\common\LogoutForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use Yii;

class LogoutAction extends PostApiAction
{
	/**
	 * Logout.
	 *
	 * @param mixed $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            Yii::$app->user->logout(false);
            $sessionUser = Yii::$app->userCache->getUser();
            $sessionUser->user_id = null;
            $sessionUser->addresses = [];

            if ($order = &$sessionUser->order) {
                $order->user_id = null;
            }

            Yii::$app->userCache->setUser($sessionUser);
            return true;
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}