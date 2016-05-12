<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu_type".
 *
 * @property string $id
 * @property string $name_key
 * @property boolean $is_option
 * @property integer $client_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property MenuItemMenuType[] $menuItemMenuTypes
 * @property MenuItem[] $menuItems
 * @property Client $client
 * @property Schedule[] $schedules
 */
class MenuType extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_key', 'client_id'], 'required'],
            [['is_option'], 'boolean'],
            [['client_id'], 'integer'],
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
            'id' => Yii::t('app', 'ID'),
            'name_key' => Yii::t('app', 'Name Key'),
            'is_option' => Yii::t('app', 'Is Option'),
            'client_id' => Yii::t('app', 'Client ID'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItemMenuTypes()
    {
        return $this->hasMany(MenuItemMenuType::className(), ['menu_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['id' => 'menu_item_id'])->viaTable('menu_item_menu_type', ['menu_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedules()
    {
        return $this->hasMany(Schedule::className(), ['menu_type_id' => 'id']);
    }
}
