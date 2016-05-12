<?php
namespace gateway\modules\v1\actions\common;

use gateway\modules\v1\forms\common\GetOrderHistoryForm;
use gateway\modules\v1\components\GetApiAction;
use Exception;
use gateway\modules\v1\forms\common\GetPostcodeForm;
use Yii;

class GetPostcodeAction extends GetApiAction
{
    /**
     * Creates request form used to validate request parameters.
     *
     * @return GetPostcodeForm
     */
    protected function createRequestForm()
    {
        return new GetPostcodeForm();
    }

	/**
	 * Get postcode
	 *
	 * @param GetPostcodeForm $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            return Yii::$app->locationService->getPostcode($requestForm->postcode);
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}