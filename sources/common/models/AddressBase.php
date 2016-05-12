<?php

namespace common\models;

use common\components\language\T;
use Yii;
use common\enums\RecordType;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "address_base".
 *
 * @property string $id
 * @property string $name
 * @property string $delivery_delay_time
 * @property double $postcode
 * @property double $latitude
 * @property double $longitude
 * @property double $max_delivery_distance
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Address[] $addresses
 * @property Restaurant[] $restaurants
 */
class AddressBase extends \common\models\BaseModel
{
    public $client_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address_base';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required', 'message' => Yii::t('error', 'Name is missing')], 
            ['name', 'common\validators\CustomUniqueValidator' , 'message' => Yii::t('label', 'name is already in use')],
            ['delivery_delay_time', 'required', 'message' => Yii::t('error', 'delivery delay time is missing') ],
            ['postcode', 'required', 'message' => Yii::t('error', 'Postcode is missing')],

            [['latitude'], 'number', 'min' => -90, 'max' => 90, 'message'=>T::e('Latitude is not a number'),'tooBig'=>T::e('Latitude can not be less than -90 degrees')],
            [['longitude'], 'number', 'min' => -180, 'max' => 180, 'message'=>T::e('Latitude is not a number'), 'tooBig'=>T::e('Longitude can not be more than 180 degrees'), 'tooSmall'=>T::e('Longitude can not be less than -180 degrees')],
            [['max_delivery_distance'], 'number', 'min' => 0, 'max' => 100000000, 'message'=>T::e('Max delivery distance is invalid'),'tooBig'=>T::e('Max delivery distance is invalid')],

            [['delivery_delay_time', 'create_on', 'last_update'], 'safe'],
            [['record_type'],  'required', 'message' => Yii::t('error', 'Record Type is missing')],
            [['name'], 'string', 'max' => 190, 'message' => Yii::t('error', 'Name is invalid')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'name' => Yii::t('label', 'Name'),
            'postcode' => Yii::t('label', 'Postcode'),
            'latitude' => Yii::t('label', 'Latitude'),
            'longitude' => Yii::t('label', 'Longitude'),
            'delivery_delay_time' => Yii::t('label', 'Delivery Delay Time'),
            'client_id' => Yii::t('label', 'Client ID'),
            'max_delivery_distance' => Yii::t('label', 'Max Delivery Distance'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['address_base_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants()
    {
        return $this->hasMany(Restaurant::className(), ['address_base_id' => 'id']);
    }

    public static function getAddressBaseForSelect()
    {
        return ArrayHelper::map(self::find()->where("record_type <> '".RecordType::Deleted."'")->all(), 'id', 'name');
    }
}
