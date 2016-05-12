<?php

namespace common\models;

use common\components\FormatHelper;
use Yii;
use \common\components\language\T;

/**
 * This is the model class for table "address".
 *
 * @property string $id
 * @property integer $country_id
 * @property string $name
 * @property string $first_name
 * @property string $last_name
 * @property string $title
 * @property string $city
 * @property string $postcode
 * @property string $address1
 * @property string $address2
 * @property string $address3
 * @property string $instructions
 * @property string $phone
 * @property string $email
 * @property string $building_number
 * @property string $latitude
 * @property string $longitude
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Country $country
 * @property CompanyAddress[] $companyAddresses
 * @property Company[] $companies
 * @property RestaurantAddress[] $restaurantAddresses
 * @property UserAddress[] $userAddresses
 */
class Address extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['address1', 'required', 'message' => T::e('Address1 is missing'),
                'whenClient' => 'function (attribute, value) { return ( $(attribute.input).is(":visible") || $("#specific_delivery option:selected").val() == "Yes" ); }'
            ],
            ['city', 'required','message' => T::e('City is missing'),
                'whenClient' => 'function (attribute, value) { return ( $(attribute.input).is(":visible") || $("#specific_delivery option:selected").val() == "Yes" ); }'
            ],
            ['country_id', 'required','message' => T::e('Country is missing'),
                'whenClient' => 'function (attribute, value) { return ( $(attribute.input).is(":visible") || $("#specific_delivery option:selected").val() == "Yes" ); }'
            ],
            ['postcode', 'required','message' => T::e('Postcode is missing'),
                'whenClient' => 'function (attribute, value) { return ( $(attribute.input).is(":visible") || $("#specific_delivery option:selected").val() == "Yes" ); }'
            ],
            [['title', 'record_type'], 'string'],
            [['building_number'], 'number', 'message' => T::e('Building number must be number')],
            [['latitude'],        'number', 'min' => -90,  'max' => 90,  'message' => T::e('Latitude is not a number'), 'tooBig' => T::e('Latitude can not be less than -90 degrees')],
            [['longitude'],       'number', 'min' => -180, 'max' => 180, 'message' => T::e('Latitude is not a number'), 'tooBig' => T::e('Longitude can not be more than 180 degrees'), 'tooSmall' => T::e('Longitude can not be less than -180 degrees')],
            [['country_id'], 'integer'], /*'address_base_id', 'city_id', 'postcode_id',*/
            [['create_on', 'last_update'], 'safe'],
            [['address1'],        'string', 'max' => 50,  'tooLong' => T::e('Address1 is too long')],
            [['address2'],        'string', 'max' => 50,  'tooLong' => T::e('Address2 is too long')],
            [['address3'],        'string', 'max' => 50,  'tooLong' => T::e('Address3 is too long')],
            [['postcode'],        'string', 'max' => 45,  'tooLong' => T::e('Postcode is too long')],
            [['phone'],           'string', 'max' => 50,  'tooLong' => T::e('Phone is too long')],
            [['city'],            'string', 'max' => 250, 'tooLong' => T::e('City is too long')],
            [['name'],            'string', 'max' => 250, 'tooLong' => T::e('Name is too long')],
            [['first_name'],      'string', 'max' => 250, 'tooLong' => T::e('First Name is too long')],
            [['last_name'],       'string', 'max' => 250, 'tooLong' => T::e('Last Name is too long')],
            [['building_number'], 'string', 'max' => 50,  'tooLong' => T::e('Building number is too long')],
            [['instructions'],    'string', 'max' => 250, 'tooLong' => T::e('Instructions is too long')],
            [['country_id'], 'integer'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['name', 'first_name', 'last_name', 'city', 'email'], 'string', 'max' => 255],
            [['title', 'postcode'], 'string', 'max' => 45],
            [['address1', 'address2', 'address3', 'phone', 'building_number'], 'string', 'max' => 50],
            [['instructions'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'              => T::l('ID'),
            'name'            => Yii::t('label', 'Name'),
            'address1'        => T::l('Address1'),
            'address2'        => T::l('Address2'),
            'address3'        => T::l('Address3'),
            'instructions'    => T::l('Instructions'),
            'latitude'        => T::l('Latitude'),
            'longitude'       => T::l('Longitude'),
            'title'           => Yii::t('label', 'Title'),
            'city'            => T::l('City'),
            'country_id'      => T::l('Country'),
            'building_number' => T::l('Building number'),
            'postcode'        => T::l('Postcode'),
            'phone'           => T::l('Phone'),
            'record_type'     => T::l('Record Type'),
            'create_on'       => T::l('Create On'),
            'last_update'     => T::l('Last Update'),
            //'address_base_id' => Yii::t('app', 'Address Base ID'),

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyAddresses()
    {
        return $this->hasMany(CompanyAddress::className(), ['address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies()
    {
        return $this->hasMany(Company::className(), ['id' => 'company_id'])->viaTable('company_address', ['address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantAddresses()
    {
        return $this->hasMany(RestaurantAddress::className(), ['address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAddresses()
    {
        return $this->hasMany(UserAddress::className(), ['address_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        $this->setValues();
    }

    public function afterFind() {
        parent::afterFind();
        $this->setValues();
    }

    private function setValues() {
        $this->latitude = $this->latitude ? round($this->latitude, 6) : null;
        $this->longitude = $this->longitude ? round($this->longitude, 6) : null;
    }
}
