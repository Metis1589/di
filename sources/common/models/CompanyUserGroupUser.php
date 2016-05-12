<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "company_user_group_user".
 *
 * @property string $id
 * @property string $user_id
 * @property integer $company_user_group_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property User $user
 * @property CompanyUserGroup $companyUserGroup
 */
class CompanyUserGroupUser extends \common\models\BaseModel
{
    public $company_user_group_name;
    public $user_username;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_user_group_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['user_id', 'required', 'message' => Yii::t('error', 'User is missing')],
            ['company_user_group_id', 'required', 'message' => Yii::t('error', 'User Group is missing')],
            [['user_id', 'company_user_group_id'], 'integer'],
            [['record_type'], 'string'],
            ['record_type', 'required',  'message' => Yii::t('error','Record Type is missing')],
            [['create_on', 'last_update'], 'safe'],
            ['user_id', 'validateUser']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'user_id' => Yii::t('label', 'User'),
            'company_user_group_id' => Yii::t('label', 'Company User Group'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
            'company_user_group_name' => Yii::t('label', 'Company Group Name'),
            'user_username' => Yii::t('label', 'User Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyUserGroup()
    {
        return $this->hasOne(CompanyUserGroup::className(), ['id' => 'company_user_group_id']);
    }
    
    public function validateUser()
    {
        $existedAssigment = self::find()->where('user_id = :user_id AND company_user_group_id = :company_user_group_id AND id <> :id AND record_type <> :record_type',
            [
                ':id' =>isset($this->id) ? $this->id : 0,
                ':user_id' => $this->user_id,
                ':company_user_group_id' => $this->company_user_group_id,
                ':record_type' => \common\enums\RecordType::Deleted
            ]        
        )->one();
        if (isset($existedAssigment)) {
            $this->addError('user_id', 'User is already assigned');
        }
    }
}
