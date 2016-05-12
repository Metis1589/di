<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu_item_similar".
 *
 * @property string $menu_item_id
 * @property string $menu_item_similar_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property MenuItem $menuItem
 * @property MenuItem $menuItemSimilar
 */
class MenuItemSimilar extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_item_similar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_item_id', 'menu_item_similar_id'], 'required'],
            [['menu_item_id', 'menu_item_similar_id'], 'integer'],
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
            'menu_item_similar_id' => Yii::t('app', 'Menu Item Similar ID'),
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
    public function getMenuItemSimilar()
    {
        return $this->hasOne(MenuItem::className(), ['id' => 'menu_item_similar_id']);
    }
}
