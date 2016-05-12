<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu_option_item".
 *
 * @property string $id
 * @property string $name_key
 * @property string $description_key
 * @property string $menu_category_id
 * @property double $web_price
 * @property double $restaurant_price
 * @property integer $order
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property MenuOptionCategoryItem[] $menuOptionCategoryItems
 * @property MenuCategory $menuCategory
 */
class MenuOptionItem extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_option_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_key', 'menu_category_id'], 'required'],
            [['menu_category_id', 'order'], 'integer'],
            [['web_price', 'restaurant_price'], 'number'],
            [['record_type'], 'string'],
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
            'description_key' => Yii::t('app', 'Description Key'),
            'menu_category_id' => Yii::t('app', 'Menu Category ID'),
            'web_price' => Yii::t('app', 'Web Price'),
            'restaurant_price' => Yii::t('app', 'Restaurant Price'),
            'order' => Yii::t('app', 'Order'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuOptionCategoryItems()
    {
        return $this->hasMany(MenuOptionCategoryItem::className(), ['menu_option_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuCategory()
    {
        return $this->hasOne(MenuCategory::className(), ['id' => 'menu_category_id']);
    }
}
