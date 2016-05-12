<?php

namespace common\models;

use common\enums\RecordType;
use yii\helpers\ArrayHelper;
use \common\components\language\T;

/**
 * This is the model class for table "company_user_group".
 *
 * @property integer $id
 * @property string $company_id
 * @property string $name
 * @property integer $max_order_per_day_per_user
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Company $company
 * @property CompanyUserGroupCode[] $companyUserGroupCodes
 * @property ExpenseType[] $expenseTypes
 * @property User[] $users
 */
class CompanyUserGroup extends \common\models\BaseModel
{
    public $company_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_user_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name',        'required',   'message' => T::e('Name is missing')],
            ['name',        'uniqueName'],
            ['name',        'string',     'max'     => 255, 'message' => T::e('Invalid name')],
            ['company_id',  'required',   'message' => T::e('Company is missing')],
            ['company_id',  'integer',    'message' => T::e('Invalid company')],
            ['company_id',  'common\validators\CustomExistValidator', 'targetClass' => '\common\models\Company', 'targetAttribute' => 'id', 'message' => T::e('Invalid company')],
            [['create_on',  'last_update'], 'safe'],
            ['record_type', 'required', 'message' => T::e('Record Type is missing')],
            ['record_type', 'string'],
            ['max_order_per_day_per_user', 'integer', 'max' => 999]
        ];
    }

    /**
     * Group name validation
     *
     * @param string $attribute
     * @param string $param
     */
    public function uniqueName($attribute, $param)
    {
        $rule  = ($this->id) ? "AND id <> {$this->id}" : '';
        $count = self::find()->where("company_id = {$this->company_id} AND name = '{$this->name}' {$rule}  AND record_type <> '" . \common\enums\RecordType::Deleted . "'")->count();

        if ($count > 0) {
            $this->addError($attribute, T::e('Group name must be unique'));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                         => T::l('ID'),
            'name'                       => T::l('Name'),
            'company_id'                 => T::l('Company ID'),
            'record_type'                => T::l('Record Type'),
            'create_on'                  => T::l('Create On'),
            'last_update'                => T::l('Last Update'),
            'max_order_per_day_per_user' => T::l('Max Order Per Day Per User'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyUserGroupCodes()
    {
        return $this->hasMany(CompanyUserGroupCode::className(), ['company_user_group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyUserGroupCodeNames()
    {
        return $this->hasMany(Code::className(), [ 'id' => 'code_id' ])->viaTable('company_user_group_code groupCode', [ 'company_user_group_id' => 'id' ], function($query) {
            $query->onCondition("groupCode.record_type = '".RecordType::Active."'");
        });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyUserGroupUsers()
    {
        return $this->hasMany(User::className(), ['company_user_group_id' => 'id'])->onCondition("user.record_type <> '".RecordType::Deleted."'");
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveCodes()
    {
        return $this->hasMany(Code::className(), ['id' => 'code_id'])->viaTable('company_user_group_code', ['company_user_group_id' => 'id'], function($query) {
            $query->onCondition(['company_user_group_code.record_type' => RecordType::Active]);
        })->andOnCondition(['code.record_type' => RecordType::Active]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveExpenseTypes()
    {
        return $this->hasMany(ExpenseType::className(), ['company_user_group_id' => 'id'])->andOnCondition(['expense_type.record_type' => RecordType::Active]);
    }

    public static function getGroupsForSelect()
    {
        return ArrayHelper::map(self::find()->where("record_type <> '".RecordType::Deleted."'")->all(), 'id', 'name');
    }

    /**
     * Save / update user group
     *
     * @param array $postedGroup
     *
     * @return \common\models\UserS
     */
    public static function saveByPost($postedGroup)
    {
        $existedGroup = static::findOne($postedGroup['id']);
        $isNew        = false;
        $codes        = false;
        $users        = false;
        if (($postedGroup['name'] == \common\enums\DefaultCompanyGroup::DefaultExternal || $postedGroup['name'] == \common\enums\DefaultCompanyGroup::DefaultInternal) && $postedGroup['record_type'] == \common\enums\RecordType::Deleted) {
            return $existedGroup;
        }

        // Group codes
        if (isset($postedGroup['companyUserGroupCodeNames'])) {
            if (!sizeof($postedGroup['companyUserGroupCodeNames'])) {
                $codes = [];
            } else {
                $codes = $postedGroup['companyUserGroupCodeNames'];
                unset($postedGroup['companyUserGroupCodeNames'], $postedGroup['codes']);
            }
        }

        // Group users
        if (isset($postedGroup['companyUserGroupUsers'])) {
            if (sizeof($postedGroup['companyUserGroupUsers'])) {
                $users = $postedGroup['companyUserGroupUsers'];
                unset($postedGroup['companyUserGroupUsers']);
            }
        }

        // Group
        if ($existedGroup == null) {
            $existedGroup = new CompanyUserGroup;
            $existedGroup->record_type = RecordType::Active;
            unset($postedGroup['id']);
            $isNew = true;
        }
        $existedGroup->load($postedGroup, '');
        $existedGroup->record_type = $postedGroup['record_type'];
        $isSaved = $existedGroup->save();

        if ($postedGroup['record_type'] !== RecordType::Deleted && $isSaved && is_array($codes)) {
            $existedGroup->_saveGroupCodes($codes);
        }

        if ($postedGroup['record_type'] !== RecordType::Deleted && $isSaved && is_array($users)) {
            $existedGroup->_saveGroupUsers($users);
        }

        return $existedGroup;
    }

    /**
     * Save company group codes list
     *
     * @param array $codes
     */
    private function _saveGroupCodes($codes)
    {
        if (sizeof($codes)) {
            foreach ($codes as $code) {
                $companyCode = CompanyUserGroupCode::find()->where("company_user_group_id = {$this->id} AND code_id = {$code['id']}")->one();
                if (!isset($code['isChecked']) || isset($code['isChecked']) && $code['isChecked'] === true) {
                    if (!$companyCode) {
                        $companyCode                        = new CompanyUserGroupCode;
                        $companyCode->company_user_group_id = $this->id;
                        $companyCode->code_id               = $code['id'];
                        $companyCode->record_type           = RecordType::Active;
                        $companyCode->save();
                    } else {
                        $companyCode->record_type = RecordType::Active;
                        $companyCode->save();
                    }
                } else if (isset($code['isChecked']) && $code['isChecked'] === false) {
                    if ($companyCode) {
                        $companyCode->record_type = RecordType::InActive;
                        $companyCode->save();
                    }
                }
            }
        } else {
            $companyCodes = CompanyUserGroupCode::find()->where("company_user_group_id = {$this->id}")->all();
            foreach ($companyCodes as $code) {
                $code->record_type = RecordType::Deleted;
                $code->save();
            }
        }
    }

    /**
     * Save company users list
     *
     * @param array $users
     */
    private function _saveGroupUsers($users)
    {
        if (sizeof($users)) {
            foreach ($users as $user) {
                if ($user['company_user_group_id'] == null) {
                    $userModel = User::findOne($user['id']);
                    $userModel->company_user_group_id = null;
                    $userModel->save();
                }
            }
        } else {
            $groupUsers = User::find()->where("company_user_group_id = {$this->id} AND record_type <> '" . RecordType::Deleted . "'");
            foreach ($groupUsers as $user) {
                $user->company_user_group_id = null;
                $user->save();
            }
        }
    }
}
