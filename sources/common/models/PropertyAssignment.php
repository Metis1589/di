<?php

namespace common\models;

use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "property_assignment".
 *
 * @property string $id
 * @property integer $client_id
 * @property string $restaurant_chain_id
 * @property string $restaurant_group_id
 * @property integer $restaurant_id
 * @property string $max_delivery_order_value
 * @property string $min_delivery_order_value
 * @property string $max_delivery_order_amount
 * @property string $min_delivery_order_amount
 * @property string $max_collection_order_value
 * @property string $min_collection_order_value
 * @property string $max_collection_order_amount
 * @property string $min_collection_order_amount
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Client $client
 */
class PropertyAssignment extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property_assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'restaurant_chain_id', 'restaurant_group_id', 'restaurant_id', 'max_delivery_order_value', 'min_delivery_order_value', 'max_delivery_order_amount', 'min_delivery_order_amount', 'max_collection_order_value', 'min_collection_order_value', 'max_collection_order_amount', 'min_collection_order_amount'], 'integer'],
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
            'max_delivery_order_value' => Yii::t('label', 'Max Delivery Order Value'),
            'min_delivery_order_value' => Yii::t('label', 'Min Delivery Order Value'),
            'max_delivery_order_amount' => Yii::t('label', 'Max Delivery Number Of Items'),
            'min_delivery_order_amount' => Yii::t('label', 'Min Delivery Number Of Items'),
            'max_collection_order_value' => Yii::t('label', 'Max Collection Order Value'),
            'min_collection_order_value' => Yii::t('label', 'Min Collection Order Value'),
            'max_collection_order_amount' => Yii::t('label', 'Max Collection Number Of Items'),
            'min_collection_order_amount' => Yii::t('label', 'Min Collection Number Of Items'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
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
    public function getRestaurantChain()
    {
        return $this->hasOne(RestaurantChain::className(), ['id' => 'restaurant_chain_id']);
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
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }

}
