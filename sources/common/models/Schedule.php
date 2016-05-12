<?php

namespace common\models;

use Yii;
use common\enums\RecordType;

/**
 * This is the model class for table "schedule".
 *
 * @property integer $id
 * @property string $from
 * @property string $to
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property CompanySchedule[] $companySchedules
 * @property ExpenseTypeSchedule[] $expenseTypeSchedules
 * @property MenuTypeSchedule[] $menuTypeSchedules
 * @property MenuType[] $menuTypes
 * @property RestaurantSchedule[] $restaurantSchedules
 * @property Restaurant[] $restaurants
 */
class Schedule extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from', 'to'], 'required'],
            [['from', 'to', 'create_on', 'last_update'], 'safe'],
            [['record_type'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'from' => Yii::t('label', 'From'),
            'to' => Yii::t('label', 'To'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanySchedules()
    {
        return $this->hasMany(CompanySchedule::className(), ['schedule_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpenseTypeSchedules()
    {
        return $this->hasMany(ExpenseTypeSchedule::className(), ['schedule_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuTypeSchedules()
    {
        return $this->hasMany(MenuTypeSchedule::className(), ['schedule_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuTypes()
    {
        return $this->hasMany(MenuType::className(), ['id' => 'menu_type_id'])->viaTable('menu_type_schedule', ['schedule_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantSchedules()
    {
        return $this->hasMany(RestaurantSchedule::className(), ['schedule_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants()
    {
        return $this->hasMany(Restaurant::className(), ['id' => 'restaurant_id'])->viaTable('restaurant_schedule', ['schedule_id' => 'id']);
    }
    
    public static function getScheduleIDForSelect()
    {
        return yii\helpers\ArrayHelper::map(self::find()->where("record_type <> '".RecordType::Deleted."'")->all(), 'id', 'id');
    }
    
    public static function getScheduleFromToForSelect()
    {
        $fromTo = yii\helpers\ArrayHelper::map(self::find()->where("record_type <> '".RecordType::Deleted."'")->all(), 'id', 'from');
        $to = yii\helpers\ArrayHelper::map(self::find()->where("record_type <> '".RecordType::Deleted."'")->all(), 'id', 'to');
        foreach ($fromTo as $k => $v) 
        {
           $fromTo[$k] = $v . ' - ' . $to[$k] ;
        }
        return $fromTo;
    }
    
    public static function getScheduleFromForSelect()
    {
        $from = yii\helpers\ArrayHelper::map(self::find()->where("record_type <> '".RecordType::Deleted."'")->all(), 'id', 'from');
        foreach ($from as $k => $v) 
        {
           $from[$k] = date('H:i', $v);
        }
        return $from;
    }
    
    
}
