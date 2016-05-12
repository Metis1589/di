<?php
namespace gateway\modules\v1\actions\common;

use common\enums\RecordType;
use common\enums\UserType;
use common\models\Code;
use common\models\ExpenseType;
use common\models\User;
use gateway\models\SessionUser;
use gateway\modules\v1\components\GetApiAction;
use Exception;
use Yii;
use yii\base\ErrorException;

class GetExpenseTypesAction extends GetApiAction
{
	/**
	 * Returns associated expense types and codes.
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

            if ($user->user_type != UserType::CorporateMember) {
                throw new ErrorException('ERR_API_GET_EXPENSE_TYPES__USER_NOT_CORP_MEMBER');
            }

            $codes = Code::find()
                ->joinWith('companyUserGroupCodes.companyUserGroups')
                ->where(
                    [
                        'code.record_type' => RecordType::Active,
                        'company_user_group_code.record_type' => RecordType::Active,
                        'company_user_group.record_type' => RecordType::Active,
                        'company_user_group.id' => $user->company_user_group_id,
                    ])
                ->asArray()
                ->all();

            $expense_types = ExpenseType::find()
                ->joinWith(['expenseTypeSchedules', 'companyUserGroup'])
                ->where(
                    [
                        'expense_type.record_type' => RecordType::Active,
                        'expense_type_schedule.record_type' => RecordType::Active,
                        'company_user_group.record_type' => RecordType::Active,
                        'company_user_group.id' => $user->company_user_group_id,
                    ])
                ->asArray()
                ->all();

            return [
                'expense_types' => $expense_types,
                'codes' => $codes
            ];
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}