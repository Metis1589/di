<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu_assignment".
 *
 * @property string $id
 * @property integer $client_id
 * @property string $restaurant_chain_id
 * @property string $restaurant_group_id
 * @property integer $restaurant_id
 * @property integer $menu_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Restaurant $restaurant
 * @property Client $client
 * @property RestaurantGroup $restaurantGroup
 * @property RestaurantChain $restaurantChain
 * @property Menu $menu
 */
class MenuAssignment extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id'], 'required'],
            [['client_id', 'restaurant_chain_id', 'restaurant_group_id', 'restaurant_id', 'menu_id'], 'integer'],
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
            'id' => Yii::t('label', 'ID'),
            'client_id' => Yii::t('label', 'Client ID'),
            'restaurant_chain_id' => Yii::t('label', 'Restaurant Chain ID'),
            'restaurant_group_id' => Yii::t('label', 'Restaurant Group ID'),
            'restaurant_id' => Yii::t('label', 'Restaurant ID'),
            'menu_id' => Yii::t('label', 'Menu ID'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
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
    public function getRestaurantGroup()
    {
        return $this->hasOne(RestaurantGroup::className(), ['id' => 'restaurant_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantChain()
    {
        return $this->hasOne(RestaurantChain::className(), ['id' => 'restaurant_chain_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }

}
