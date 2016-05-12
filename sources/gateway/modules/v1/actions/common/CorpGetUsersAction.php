<?php

namespace gateway\modules\v1\actions\common;

use common\enums\UserType;
use common\models\Company;
use Exception;
use gateway\models\SessionUser;
use gateway\modules\v1\components\GetApiAction;
use gateway\modules\v1\forms\common\CorpGetUsersForm;
use Yii;

class CorpGetUsersAction extends GetApiAction {

    /**
     * Creates request form used to validate request parameters.
     *
     * @return CorpGetUsersForm
     */
    protected function createRequestForm()
    {
        return new CorpGetUsersForm();
    }

    /**
     * Returns order information.
     *
     * @param CorpGetUsersForm $requestForm Request form class instance.
     *
     * @return string|boolean
     */
    protected function getResponseData($requestForm) {
        try {

            if (Yii::$app->user->identity->user_type == UserType::CorporateMember) {
                /** @var SessionUser $session_user */
                $session_user = Yii::$app->userCache->getUser();

                if ((isset($session_user->expense_type) && isset($requestForm->expense_type_id) && $session_user->expense_type['id'] != $requestForm->expense_type_id) || !isset($session_user->expense_type)) {
                    Yii::$app->corporateOrderService->setCurrentUserAsCorp($requestForm->expense_type_id);
                }

                return Yii::$app->corporateOrderService->getApiResponse();
            }
            return [];

        } catch (Exception $ex) {
            return $ex;
        }
    }

}
