<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property string $id
 * @property string $code
 * @property string $name
 * @property double $daily_limit
 * @property double $weekly_limit
 * @property double $monthly_limit
 * @property string $limit_type
 * @property string $company_id
 * @property string $user_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property CorporateOrder[] $corporateOrders
 * @property Company $company
 * @property User $user
 */
class Project extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['code', 'required','message' => Yii::t('error', 'Code is missing')],
            ['code', 'string', 'max' => 255, 'message' => Yii::t('error', 'Code is invalid')],
            ['code', 'common\validators\CustomUniqueValidator', 'message' => Yii::t('label', 'code is already in use')],
            
            ['name', 'required','message' => Yii::t('error', 'Name is missing')],
            ['name', 'string', 'max' => 255, 'message' => Yii::t('error', 'Name is invalid')],
            ['name', 'common\validators\CustomUniqueValidator', 'message' => Yii::t('label', 'name is already in use')],
            
            ['company_id', 'required','message' => Yii::t('error', 'Company is missing')],
            ['company_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\Company', 'targetAttribute' => 'id',  'message' => Yii::t('label', 'Invalid company')],
            ['user_id', 'required','message' => Yii::t('error', 'User is missing')],        
            ['user_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\User', 'targetAttribute' => 'id',  'message' => Yii::t('label', 'Invalid user')],
            
            [['daily_limit', 'weekly_limit', 'monthly_limit'], 'required','message' => Yii::t('error', 'Limit is missing')],
            [['daily_limit', 'weekly_limit', 'monthly_limit'], 'double', 'min' => 0,  'max' => 1.0E8, 'message' => Yii::t('error', 'Limit is invalid'),
               'tooBig' => Yii::t('error', 'Limit is invalid'), 'tooSmall' => Yii::t('error', 'Limit is invalid')],
            
            [['limit_type', 'record_type'], 'string'],
                        
            ['record_type',  'required', 'message' => Yii::t('error', 'Record Type is missing')],
            ['limit_type',  'required', 'message' => Yii::t('error', 'Limit Type is missing')],
            
            [['company_id', 'user_id'], 'integer'],
            [['create_on', 'last_update'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'code' => Yii::t('label', 'Code'),
            'name' => Yii::t('label', 'Name'),
            'daily_limit' => Yii::t('label', 'Daily Limit'),
            'weekly_limit' => Yii::t('label', 'Weekly Limit'),
            'monthly_limit' => Yii::t('label', 'Monthly Limit'),
            'limit_type' => Yii::t('label', 'Limit Type'),
            'company_id' => Yii::t('label', 'Company ID'),
            'user_id' => Yii::t('label', 'User ID'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCorporateOrders()
    {
        return $this->hasMany(CorporateOrder::className(), ['project_id' => 'id']);
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
