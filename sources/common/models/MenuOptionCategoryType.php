<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu_option_category_type".
 *
 * @property string $id
 * @property string $name_key
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property MenuOption[] $menuOptions
 */
class MenuOptionCategoryType extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_option_category_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_key'], 'required'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['name_key'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'name_key' => Yii::t('label', 'Name Key'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuOptions()
    {
        return $this->hasMany(MenuOption::className(), ['menu_option_category_type_id' => 'id']);
    }
}
