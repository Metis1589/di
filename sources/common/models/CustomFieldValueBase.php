<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "custom_field_value".
 *
 * @property string $id
 * @property string $custom_field_id
 * @property integer $restaurant_id
 * @property string $menu_item_id
 * @property string $restaurant_delivery_charge_id
 * @property string $value
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property CustomField $customField
 * @property Restaurant $restaurant
 * @property MenuItem $menuItem
 * @property RestaurantDeliveryCharge $restaurantDeliveryCharge
 */
class CustomFieldValueBase extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'custom_field_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['custom_field_id', 'value'], 'required'],
            [['id', 'custom_field_id', 'restaurant_id', 'menu_item_id', 'restaurant_delivery_charge_id'], 'integer'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['value'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'custom_field_id' => Yii::t('label', 'Custom Field ID'),
            'restaurant_id' => Yii::t('label', 'Restaurant ID'),
            'menu_item_id' => Yii::t('label', 'Menu Item ID'),
            'restaurant_delivery_charge_id' => Yii::t('label', 'Restaurant Delivery Charge ID'),
            'value' => Yii::t('label', 'Value'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomField()
    {
        return $this->hasOne(CustomField::className(), ['id' => 'custom_field_id']);
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
    public function getMenuItem()
    {
        return $this->hasOne(MenuItem::className(), ['id' => 'menu_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDeliveryCharge()
    {
        return $this->hasOne(RestaurantDeliveryCharge::className(), ['id' => 'restaurant_delivery_charge_id']);
    }
}
