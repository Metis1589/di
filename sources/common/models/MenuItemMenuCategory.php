<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu_item_menu_category".
 *
 * @property string $menu_item_id
 * @property string $menu_category_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property MenuItem $menuItem
 * @property MenuCategory $menuCategory
 */
class MenuItemMenuCategory extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_item_menu_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_item_id', 'menu_category_id'], 'required'],
            [['menu_item_id', 'menu_category_id'], 'integer'],
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
            'menu_item_id' => Yii::t('app', 'Menu Item ID'),
            'menu_category_id' => Yii::t('app', 'Menu Category ID'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItem()
    {
        return $this->hasOne(MenuItem::className(), ['id' => 'menu_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuCategory()
    {
        return $this->hasOne(MenuCategory::className(), ['id' => 'menu_category_id']);
    }
}
