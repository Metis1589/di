<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu_option_category".
 *
 * @property string $id
 * @property string $name_key
 * @property double $web_price
 * @property double $restaurant_price
 * @property integer $items_limit
 * @property string $description_key
 * @property string $view_type
 * @property string $price_calc_type
 * @property string $menu_option_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property MenuOption $menuOption
 * @property MenuOptionCategoryItem[] $menuOptionCategoryItems
 */
class MenuOptionCategory extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_option_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_key', 'menu_option_id'], 'required'],
            [['web_price', 'restaurant_price'], 'number'],
            [['items_limit', 'menu_option_id'], 'integer'],
            [['view_type', 'price_calc_type', 'record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['name_key', 'description_key'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name_key' => Yii::t('app', 'Name Key'),
            'web_price' => Yii::t('app', 'Web Price'),
            'restaurant_price' => Yii::t('app', 'Restaurant Price'),
            'items_limit' => Yii::t('app', 'Items Limit'),
            'description_key' => Yii::t('app', 'Description Key'),
            'view_type' => Yii::t('app', 'View Type'),
            'price_calc_type' => Yii::t('app', 'Price Calc Type'),
            'menu_option_id' => Yii::t('app', 'Menu Option ID'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuOption()
    {
        return $this->hasOne(MenuOption::className(), ['id' => 'menu_option_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuOptionCategoryItems()
    {
        return $this->hasMany(MenuOptionCategoryItem::className(), ['menu_option_category_id' => 'id']);
    }
}
