<?php
namespace gateway\modules\v1\actions\common;

use gateway\modules\v1\forms\common\GetClientDataLoadTimeForm;
use gateway\modules\v1\components\GetApiAction;
use Exception;
use Yii;

class GetClientDataLoadTimeAction extends GetApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return GetClientDataLoadTimeForm
	 */
	protected function createRequestForm()
	{
		return new GetClientDataLoadTimeForm();
	}

	/**
	 * get client data last update time.
	 *
	 * @param GetClientDataLoadTimeForm $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            return Yii::$app->globalCache->getDataLoadTime($requestForm->client_key);
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}