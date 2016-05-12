<?php
namespace gateway\modules\v1\actions\common;

use gateway\modules\v1\services\LoyaltyService;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use Yii;

class GenerateVoucherByPoints extends PostApiAction
{

	/**
	 * Generate one vouchet by user loyality points
	 *
	 * @param $requestForm is null
	 *
	 * @return boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            if (!LoyaltyService::generateVoucherByUser(Yii::$app->user->identity->id)){
                throw new \yii\base\ErrorException("Error generating voucher");
            }

            return Yii::$app->user->identity->loyalty_points;
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}