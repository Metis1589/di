<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu_bundle".
 *
 * @property string $id
 * @property string $name_key
 * @property string $description_key
 * @property double $restaurant_price
 * @property double $web_price
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property MenuItemMenuBundle[] $menuItemMenuBundles
 * @property MenuItem[] $menuItems
 */
class MenuBundle extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_bundle';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_key', 'web_price'], 'required'],
            [['restaurant_price', 'web_price'], 'number'],
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
    public function getMenuItemMenuBundles()
    {
        return $this->hasMany(MenuItemMenuBundle::className(), ['menu_bundle_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['id' => 'menu_item_id'])->viaTable('menu_item_menu_bundle', ['menu_bundle_id' => 'id']);
    }
}
