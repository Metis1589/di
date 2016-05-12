<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu_item_menu_type".
 *
 * @property string $menu_item_id
 * @property string $menu_type_id
 * @property double $restaurant_price
 * @property double $web_price
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property MenuItem $menuItem
 * @property MenuType $menuType
 */
class MenuItemMenuType extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_item_menu_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_item_id', 'menu_type_id', 'restaurant_price', 'web_price'], 'required'],
            [['menu_item_id', 'menu_type_id'], 'integer'],
            [['restaurant_price', 'web_price'], 'number'],
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
            'menu_type_id' => Yii::t('app', 'Menu Type ID'),
            'restaurant_price' => Yii::t('app', 'Restaurant Price'),
            'web_price' => Yii::t('app', 'Web Price'),
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
    public function getMenuType()
    {
        return $this->hasOne(MenuType::className(), ['id' => 'menu_type_id']);
    }
}
