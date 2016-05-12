<?php
namespace gateway\modules\v1\forms\common;

use common\components\language\T;
use common\enums\UserType;
use gateway\modules\v1\forms\BaseRequestApiForm;
use Yii;

class CorpGetUsersForm extends BaseRequestApiForm
{
    public $expense_type_id;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
        //    ['expense_type_id', 'required', 'message' => T::e('Expense type is missing')],

            ['expense_type_id', 'integer', 'message' => T::e('Invalid Expense type')],
            ['expense_type_id', 'validateUser', 'skipOnEmpty' => false],
            ['expense_type_id', 'validateCompany', 'skipOnEmpty' => false],
            ['expense_type_id', 'validateExpenseType', 'skipOnEmpty' => false],
        ];
    }

    public function validateCompany($attribute, $params) {
        $company = Yii::$app->corporateOrderService->getActiveCompany(Yii::$app->user->identity->company_id);
        if (!isset($company)) {
            $this->addError('expense_type_id', T::e('User has no active company'));
        }
    }

    public function validateExpenseType() {
        $userGroup =  Yii::$app->corporateOrderService->getUserGroup(Yii::$app->user->identity->companyUserGroup->company_id, Yii::$app->user->identity->company_user_group_id);
        if (!isset($userGroup['activeExpenseTypes'][0])) {
            $this->addError('expense_type_id', T::e('User company has no expense types'));
        }
    }

    public function validateUser() {
        if (!Yii::$app->user->identity->is_corporate_approved) {
            $this->addError('expense_type_id', T::e('Corporate account is not approved'));
        }
    }

}