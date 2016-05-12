<?php

namespace common\models;

use Yii;
use common\enums\RecordType;

/**
 * This is the model class for table "client".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $url
 * @property string $contact_email
 * @property string $mc_host
 * @property string $mc_api_key
 * @property string $mc_default_city_list_name
 * @property string $mc_default_restaurant_list_name
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 * @property string $eagle_eye_username
 * @property string $eagle_eye_password
 * @property string $eagle_eye_endpoint
 * @property Boolean $has_inntouch
 *
 * @property AddressBase[] $addressBases
 * @property BestForItem[] $bestForItems
 * @property ClientContact[] $clientContacts
 * @property Contact[] $contacts
 * @property Company[] $companies
 * @property Cuisine[] $cuisines
 * @property DefaultDeliveryCharges[] $defaultDeliveryCharges
 * @property ExpenseType[] $expenseTypes
 * @property FeedbackType[] $feedbackTypes
 * @property MenuType[] $menuTypes
 * @property Navigation[] $navigations
 * @property Page[] $pages
 * @property RestaurantChain[] $restaurantChains
 */
class Client extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required', 'message' => Yii::t('error', 'Name is missing')],
            [['contact_email'], 'required', 'message' => Yii::t('error', 'Contact Email is missing')],
            ['name', 'common\validators\CustomUniqueValidator', 'message' => Yii::t('label', 'name is already in use')],
            [['record_type'], 'required', 'message' => Yii::t('error', 'Record Type is missing')],
            [['create_on', 'last_update', 'has_inntouch', 'is_corporate_accounts_enabled'], 'safe'],
            [['voucher_id', 'loyalty_points_per_currency', 'loyalty_points_per_voucher'], 'integer'],
            [['name'], 'string', 'max' => 150, 'message' => Yii::t('error', 'Name is invalid')],
            [['payment_merchant_account'], 'string', 'max' => 100, 'message' => Yii::t('error', 'Merchant account is invalid')],
            [['payment_skin_code'], 'string', 'max' => 100, 'message' => Yii::t('error', 'Skin code is invalid')],
            [['description'], 'string', 'max' => 500, 'message' => Yii::t('error', 'Description is invalid')],
            [['mc_host'], 'string', 'max' => 255, 'message' => Yii::t('error', 'MailChimp Host is invalid')],
            [['mc_api_key'], 'string', 'max' => 255, 'message' => Yii::t('error', 'MailChimp Api Key is invalid')],
            [['mc_default_city_list_name'], 'string', 'max' => 255, 'message' => Yii::t('error', 'List Name is invalid')],
            [['mc_default_restaurant_list_name'], 'string', 'max' => 255, 'message' => Yii::t('error', 'List Name is invalid')],
            [['payment_hmac_key'], 'string', 'max' => 255, 'message' => Yii::t('error', 'HmacKey is invalid')],
            [['eagle_eye_username'], 'string', 'max' => 100, 'message' => Yii::t('error', 'Eagle Eye username is invalid')],
            [['eagle_eye_password'], 'string', 'max' => 100, 'message' => Yii::t('error', 'Eagle Eye password is invalid')],
            [['eagle_eye_endpoint'], 'string', 'max' => 100, 'message' => Yii::t('error', 'Eagle Eye endpoint is invalid')],
            [['url'], 'url', 'message' => Yii::t('error', 'Url is invalid')],
            [['contact_email'], 'email', 'message' => Yii::t('error', 'Contact Email is invalid')],
            ['url', 'common\validators\CustomUniqueValidator', 'message' => Yii::t('label', 'url is already in use')],
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
            'description' => Yii::t('label', 'Description'),
            'url' => Yii::t('label', 'Url'),
            'contact_email' => Yii::t('label', 'Contact Email'),
            'mc_host' => Yii::t('label', 'MailChimp Host'),
            'mc_api_key' => Yii::t('label', 'MailChimp Api Key'),
            'mc_default_city_list_name' => Yii::t('label', 'MailChimp Default City List Name'),
            'mc_default_restaurant_list_name' => Yii::t('label', 'MailChimp Default Restaurant List Name'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
            'loyalty_points_per_currency' => Yii::t('label', 'Loyalty Points Per Currency'),
            'loyalty_points_per_voucher' => Yii::t('label', 'Loyalty Points Per Voucher'),
            'voucher_id' => Yii::t('label', 'Voucher'),
            'has_inntouch' => Yii::t('label', 'Has Inntouch')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddressBases()
    {
        return $this->hasMany(AddressBase::className(), ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBestForItems()
    {
        return $this->hasMany(BestForItem::className(), ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientContacts()
    {
        return $this->hasMany(ClientContact::className(), ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['id' => 'contact_id'])->viaTable('client_contact', ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies()
    {
        return $this->hasMany(Company::className(), ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCuisines()
    {
        return $this->hasMany(Cuisine::className(), ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefaultDeliveryCharges()
    {
        return $this->hasMany(DefaultDeliveryCharges::className(), ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpenseTypes()
    {
        return $this->hasMany(ExpenseType::className(), ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeedbackTypes()
    {
        return $this->hasMany(FeedbackType::className(), ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuTypes()
    {
        return $this->hasMany(MenuType::className(), ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNavigations()
    {
        return $this->hasMany(Navigation::className(), ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucher()
    {
        return $this->hasOne(Voucher::className(), ['id' => 'voucher_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantChains()
    {
        return $this->hasMany(RestaurantChain::className(), ['client_id' => 'id']);
    }
    
    public static function getActive()
    {
        return self::findAll(['record_type' => 'Active']);
    }
    
    public static function getClientsForSelect()
    {
        return yii\helpers\ArrayHelper::map(self::find()->where("record_type <> '".RecordType::Deleted."'")->all(), 'id', 'name');
    }

    public static function getDeliveryService($client_id) {
        return RestaurantDelivery::find()->joinWith(['restaurantDeliveryCharges'])->where(['client_id' => $client_id])->andWhere(['restaurant_delivery.record_type' => RecordType::Active])->one();
    }
}
