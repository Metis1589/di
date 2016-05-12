<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu_item_menu_bundle".
 *
 * @property string $menu_item_id
 * @property string $menu_bundle_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property MenuItem $menuItem
 * @property MenuBundle $menuBundle
 */
class MenuItemMenuBundle extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_item_menu_bundle';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_item_id', 'menu_bundle_id'], 'required'],
            [['menu_item_id', 'menu_bundle_id'], 'integer'],
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
            'menu_bundle_id' => Yii::t('app', 'Menu Bundle ID'),
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
    public function getMenuBundle()
    {
        return $this->hasOne(MenuBundle::className(), ['id' => 'menu_bundle_id']);
    }
}
