<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu_option_category_item".
 *
 * @property string $id
 * @property string $menu_option_item_id
 * @property string $menu_option_category_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property MenuOptionItem $menuOptionItem
 * @property MenuOptionCategory $menuOptionCategory
 */
class MenuOptionCategoryItem extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_option_category_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_option_item_id', 'menu_option_category_id'], 'required'],
            [['menu_option_item_id', 'menu_option_category_id'], 'integer'],
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
            'id' => Yii::t('app', 'ID'),
            'menu_option_item_id' => Yii::t('app', 'Menu Option Item ID'),
            'menu_option_category_id' => Yii::t('app', 'Menu Option Category ID'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuOptionItem()
    {
        return $this->hasOne(MenuOptionItem::className(), ['id' => 'menu_option_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuOptionCategory()
    {
        return $this->hasOne(MenuOptionCategory::className(), ['id' => 'menu_option_category_id']);
    }
}
