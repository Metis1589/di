<?php
namespace gateway\modules\v1\actions\common;

use gateway\models\SessionUser;
use gateway\modules\v1\forms\common\SetDriverChargeForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use Yii;

class SetDriverChargeAction extends PostApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return SetDriverChargeForm
	 */
	protected function createRequestForm()
	{
		return new SetDriverChargeForm();
	}

	/**
	 * Set driver charge.
	 *
	 * @param SetDriverChargeForm $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            /** @var SessionUser $session_user */
            $session_user = Yii::$app->userCache->getUser();

            $session_user->driver_charge = $requestForm->driver_charge;

            Yii::$app->userCache->setUser($session_user);

            return true;
		}
		catch (Exception $ex) {
			return false;
		}
	}
}