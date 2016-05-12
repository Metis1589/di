<?php

namespace common\models;

use common\enums\RecordType;
use Yii;

/**
 * This is the model class for table "restaurant_delivery_charge".
 *
 * @property string $id
 * @property double $distance_in_miles
 * @property double $charge
 * @property string $restaurant_delivery_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property CustomFieldValue[] $customFieldValues
 * @property RestaurantDelivery $restaurantDelivery
 */
class RestaurantDeliveryCharge extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_delivery_charge';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['charge', 'required', 'message' => Yii::t('error', 'Charge is missing')],

            ['distance_in_miles', 'required', 'message' => Yii::t('error', 'Distance is missing')],

            ['charge', 'number', 'min' => 0, 'max' => 100000, 'message' => Yii::t('error', 'Invalid Charge'), 'tooBig' => Yii::t('error', 'Charge is too big'), 'tooSmall' => Yii::t('error', 'Charge is too small')],
            ['distance_in_miles', 'number', 'min' => 0, 'max' => 100000, 'message' => Yii::t('error', 'Invalid Distance'), 'tooBig' => Yii::t('error', 'Distance is too big'), 'tooSmall' => Yii::t('error', 'Distance is too small')],
            ['restaurant_delivery_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\RestaurantDelivery', 'targetAttribute' => 'id',  'message' => Yii::t('label', 'Invalid restaurant')],
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
            'distance_in_miles' => Yii::t('label', 'Distance In Mile'),
            'charge' => Yii::t('label', 'Charge'),
            'restaurant_delivery_id' => Yii::t('label', 'Restaurant Delivery ID'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomFieldValues()
    {
        return $this->hasMany(CustomFieldValue::className(), ['restaurant_delivery_charge_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDelivery()
    {
        return $this->hasOne(RestaurantDelivery::className(), ['id' => 'restaurant_delivery_id']);
    }

    /**
     * @param $field_key
     * @return string
     */
    public function getRestaurantDeliveryChargeCustomFieldValue($field_key) {
        $customFieldValue = CustomFieldValue::find()->joinWith('customField')->where([
            'custom_field.key' => $field_key,
            'custom_field_value.restaurant_delivery_charge_id' => $this->id,
            'custom_field.record_type' => RecordType::Active,
            'custom_field_value.record_type' => RecordType::Active
        ])->one();

        if (isset($customFieldValue)) {
            return $customFieldValue->value;
        }

        return null;
    }
}
