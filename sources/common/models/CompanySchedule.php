<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "company_schedule".
 *
 * @property integer $id
 * @property double $min
 * @property integer $schedule_id
 * @property string $company_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Schedule $schedule
 * @property Company $company
 */
class CompanySchedule extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['min', 'schedule_id', 'company_id'], 'required'],
            [['min'], 'number'],
            [['schedule_id', 'company_id'], 'integer'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'min' => Yii::t('app', 'Min'),
            'schedule_id' => Yii::t('app', 'Schedule ID'),
            'company_id' => Yii::t('app', 'Company ID'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedule()
    {
        return $this->hasOne(Schedule::className(), ['id' => 'schedule_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
