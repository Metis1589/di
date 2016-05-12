<?php
namespace gateway\modules\v1\actions\common;

use common\enums\DefaultCompanyGroup;
use common\enums\RecordType;
use common\models\Company;
use common\models\CompanyUserGroup;
use common\models\CorporateOrder;
use common\models\User;
use gateway\models\SessionUser;
use gateway\modules\v1\forms\common\AddReviewForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use gateway\modules\v1\forms\common\CorpSetUserForm;
use Yii;

class CorpSetUserAction extends PostApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return AddReviewForm
	 */
	protected function createRequestForm()
	{
		return new CorpSetUserForm();
	}

	/**
	 * Add review.
	 *
	 * @param CorpSetUserForm $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {

            Yii::$app->corporateOrderService->setCorporateOrderUser($requestForm->client_key, $requestForm->first_name, $requestForm->last_name, $requestForm->email,
                $requestForm->company, $requestForm->index);

            return Yii::$app->corporateOrderService->getApiResponse();
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}