<?php

namespace common\models;

use \common\components\language\T;
use Yii;

/**
 * This is the model class for table "code".
 *
 * @property string $id
 * @property string $company_id
 * @property string $name
 * @property string $value
 * @property double $daily_limit
 * @property double $weekly_limit
 * @property double $monthly_limit
 * @property string $limit_type
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Company $company
 * @property CompanyUserGroupCode[] $companyUserGroupCodes
 */
class Code extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'name', 'value', 'limit_type'], 'required'],
            ['company_id',  'integer'],
            [['daily_limit', 'weekly_limit', 'monthly_limit'], 'number'],
            [['limit_type', 'record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['name', 'value'], 'string', 'max' => 255],
            ['daily_limit',   'integer', 'max' => 99999999999999999999],
            ['weekly_limit',  'integer', 'max' => 99999999999999999999],
            ['monthly_limit', 'integer', 'max' => 99999999999999999999]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => T::l('ID'),
            'company_id'    => T::l('Company ID'),
            'name'          => T::l('Name'),
            'value'         => T::l('Value'),
            'daily_limit'   => T::l('Daily Limit'),
            'weekly_limit'  => T::l('Weekly Limit'),
            'monthly_limit' => T::l('Monthly Limit'),
            'limit_type'    => T::l('Limit Type'),
            'record_type'   => T::l('Record Type'),
            'create_on'     => T::l('Create On'),
            'last_update'   => T::l('Last Update'),
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
        return $this->hasMany(CompanyUserGroupCode::className(), ['code_id' => 'id']);
    }

    /**
     * Save / update group code
     *
     * @param array $postedCode
     *
     * @return \common\models\UserS
     */
    public static function saveByPost($postedCode)
    {
        $existedCode = static::findOne($postedCode['id']);
        $isNew       = false;

        if ($existedCode == null) {
            $existedCode = new Code;
            $existedCode->record_type = \common\enums\RecordType::Active;
            unset($postedCode['id']);
            $isNew = true;
        }
        $existedCode->load($postedCode, '');
        $existedCode->record_type = $postedCode['record_type'];
        $existedCode->save();

        return $existedCode;
    }


}
