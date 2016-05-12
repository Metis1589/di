<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu_type_schedule".
 *
 * @property integer $schedule_id
 * @property string $menu_type_id
 *
 * @property Schedule $schedule
 * @property MenuType $menuType
 */
class MenuTypeSchedule extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_type_schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['schedule_id', 'menu_type_id'], 'required'],
            [['schedule_id', 'menu_type_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'schedule_id' => Yii::t('label', 'Schedule ID'),
            'menu_type_id' => Yii::t('label', 'Menu Type ID'),
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
    public function getMenuType()
    {
        return $this->hasOne(MenuType::className(), ['id' => 'menu_type_id']);
    }
}
