<?php

namespace common\models;

use \common\components\language\T;
use Yii;

/**
 * This is the model class for table "company_user_group_code".
 *
 * @property string $id
 * @property integer $company_user_group_id
 * @property string $code_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property CompanyUserGroup $companyUserGroup
 * @property Code $code
 */
class CompanyUserGroupCode extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_user_group_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_user_group_id', 'code_id'], 'required'],
            [['company_user_group_id', 'code_id'], 'integer'],
            [['create_on', 'last_update'], 'safe'],
            ['record_type',   'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                    => T::l('ID'),
            'company_user_group_id' => T::l('Company User Group ID'),
            'code_id'               => T::l('Code ID'),
            'record_type'           => T::l('Record Type'),
            'create_on'             => T::l('Create On'),
            'last_update'           => T::l('Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyUserGroup()
    {
        return $this->hasOne(CompanyUserGroup::className(), ['id' => 'company_user_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCode()
    {
        return $this->hasOne(Code::className(), ['id' => 'code_id']);
    }

}