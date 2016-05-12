<?php
namespace gateway\modules\v1\actions\common;

use common\models\User;
use gateway\modules\v1\forms\common\GetUserForm;
use gateway\modules\v1\components\GetApiAction;
use Exception;
use Yii;

class GetUserProfileAction extends GetApiAction
{
	/**
	 * get user profile.
	 *
	 * @param mixed $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            /** @var User $user */
            $user = Yii::$app->user->identity;
            return [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->username,
                'loyalty_points' => $user->loyalty_points == null ? 0 : (int)$user->loyalty_points
            ];
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}