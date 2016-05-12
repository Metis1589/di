<?php
namespace gateway\modules\v1\actions\common;

use common\enums\RecordType;
use common\models\Code;
use common\models\Company;
use common\models\CorporateOrder;
use common\models\User;
use gateway\models\SessionUser;
use gateway\modules\v1\forms\common\AddReviewForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use gateway\modules\v1\forms\common\CorpSetUserDataForm;
use Yii;

class CorpSetUserDataAction extends PostApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return AddReviewForm
	 */
	protected function createRequestForm()
	{
		return new CorpSetUserDataForm();
	}

	/**
	 * Add review.
	 *
	 * @param CorpSetUserDataForm $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            /** @var SessionUser $session_user */
            $session_user = Yii::$app->userCache->getUser();

            $corporateOrder = &$session_user->corp_users[$requestForm->index];

            if (!empty($requestForm->code_id)) {
                //TODO get from cache
                $corporateOrder->code_data = serialize(Code::find()->where(['id' => $requestForm->code_id, 'record_type' => RecordType::Active])->one()->attributes);
            }

            $corporateOrder->allocation = $requestForm->allocation;
            $corporateOrder->comment = $requestForm->comment;

            Yii::$app->userCache->setUser($session_user);
            return true;
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}