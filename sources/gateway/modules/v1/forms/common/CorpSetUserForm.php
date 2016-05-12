<?php
namespace gateway\modules\v1\forms\common;

use common\components\language\T;
use common\enums\DefaultCompanyGroup;
use gateway\modules\v1\forms\BaseRequestApiForm;
use Yii;

class CorpSetUserForm extends BaseRequestApiForm
{
    public $index;
    public $first_name;
    public $last_name;
    public $email;
    public $company;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    protected function customRules()
    {
        return [
            ['company', 'validateExpenseType'],
        ]; // todo
    }

    public function validateExpenseType()
    {
        $client = Yii::$app->globalCache->getClient($this->client_key);
        $userGroup = Yii::$app->corporateOrderService->getUserGroupForCorpUser($this->email, $client['id']);

        if (!isset($userGroup)) {
            $userGroup = Yii::$app->corporateOrderService->getUserGroupByName(Yii::$app->user->identity->companyUserGroup->company_id,DefaultCompanyGroup::DefaultExternal);
        }

        if (!isset($userGroup['activeExpenseTypes'][0])) {
            $this->addError('expense_type_id', T::e('User company has no expense types'));
        }
    }

}