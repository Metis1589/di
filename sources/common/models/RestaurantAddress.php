<?php

namespace common\models;

use Yii;
use common\components\language\T;

/**
 * This is the model class for table "restaurant_contact".
 *
 * @property integer $id
 * @property integer $restaurant_id
 * @property integer $contact_id
 * @property string $role
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Restaurant $restaurant
 * @property Contact $contact
 */
class RestaurantAddress extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['restaurant_id', 'address_id'], 'required'],
            [['restaurant_id', 'address_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'restaurant_id' => Yii::t('label', 'Restaurant'),
            'role' => T::l('Role'),
            'record_type' => T::l('Record Type'),
            'address1' => T::l('Address1'),
            'address2' => T::l('Address2'),
            'address3' => T::l('Address3'),
            'instructions' => T::l('Instructions'),
            'latitude' => T::l('Latitude'),
            'longitude' => T::l('Longitude'),
            //'address_base_id' => Yii::t('app', 'Address Base ID'),
            'city_id' => T::l('City'),
            'country_id' => T::l('Country'),
            'postcode' => T::l('Postcode'),
            'create_on' => T::l('Create On'),
            'last_update' => T::l('Last Update'),
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
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id']);
    }
}
