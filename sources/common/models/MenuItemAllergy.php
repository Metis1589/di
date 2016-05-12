<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu_item_allergy".
 *
 * @property string $menu_item_id
 * @property integer $allergy_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Allergy $allergy
 * @property MenuItem $menuItem
 */
class MenuItemAllergy extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_item_allergy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_item_id', 'allergy_id'], 'required'],
            [['menu_item_id', 'allergy_id'], 'integer'],
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
            'menu_item_id' => Yii::t('label', 'Menu Item ID'),
            'allergy_id' => Yii::t('label', 'Allergy ID'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAllergy()
    {
        return $this->hasOne(Allergy::className(), ['id' => 'allergy_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItem()
    {
        return $this->hasOne(MenuItem::className(), ['id' => 'menu_item_id']);
    }
}
