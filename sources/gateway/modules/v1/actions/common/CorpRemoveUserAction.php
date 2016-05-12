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
use gateway\modules\v1\forms\common\CorpRemoveUserForm;
use gateway\modules\v1\forms\common\CorpSetUserForm;
use Yii;

class CorpRemoveUserAction extends PostApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return AddReviewForm
	 */
	protected function createRequestForm()
	{
		return new CorpRemoveUserForm();
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
            /** @var SessionUser $session_user */
            $session_user = Yii::$app->userCache->getUser();

            unset($session_user->corp_users[$requestForm->index]);

            $session_user->corp_users = array_values($session_user->corp_users);

            Yii::$app->userCache->setUser($session_user);

            return Yii::$app->corporateOrderService->getApiResponse();

		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}