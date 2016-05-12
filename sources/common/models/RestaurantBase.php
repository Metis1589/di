<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant".
 *
 * @property integer $id
 * @property integer $client_id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property integer $price_range
 * @property string $vat_number
 * @property string $opening_day
 * @property string $trading_name
 * @property string $default_preparation_time
 * @property string $default_cook_time
 * @property string $logo_file_name
 * @property integer $is_newest
 * @property string $seo_title
 * @property string $meta_text
 * @property string $meta_description
 * @property integer $have_app
 * @property integer $is_featured
 * @property integer $is_from_signup
 * @property string $currency_id
 * @property string $address_base_id
 * @property string $restaurant_group_id
 * @property string $seo_area_id
 * @property string $dispatch_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property CustomFieldValue[] $customFieldValues
 * @property Feedback[] $feedbacks
 * @property MenuAssignment[] $menuAssignments
 * @property Order[] $orders
 * @property PropertyAssignment[] $propertyAssignments
 * @property ReportOrder[] $reportOrders
 * @property AddressBase $addressBase
 * @property Client $client
 * @property Currency $currency
 * @property SeoArea $seoArea
 * @property RestaurantAddress[] $restaurantAddresses
 * @property RestaurantContact[] $restaurantContacts
 * @property RestaurantContactOrder[] $restaurantContactOrders
 * @property RestaurantCuisine[] $restaurantCuisines
 * @property RestaurantDelivery[] $restaurantDeliveries
 * @property RestaurantPayment[] $restaurantPayments
 * @property RestaurantPhoto[] $restaurantPhotos
 * @property RestaurantSchedule[] $restaurantSchedules
 * @property User[] $users
 * @property Voucher[] $vouchers
 */
class RestaurantBase extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'trading_name', 'vat', 'cook_time', 'avg_prepare_time', 'is_newest', 'seo_title', 'meta_text', 'meta_description', 'driver_delay_time', 'is_featured', 'currency_id', 'address_base_id', 'restaurant_group_id', 'seo_area_id'], 'required'],
            [['vat', 'price_range', 'avg_prepare_time', 'is_newest', 'default_food_prep_time', 'current_food_prep_time', 'have_app', 'is_featured', 'is_from_signup', 'currency_id', 'address_base_id', 'restaurant_group_id', 'seo_area_id'], 'integer'],
            [['cook_time', 'opening_day', 'create_on', 'last_update'], 'safe'],
            [['driver_delay_time'], 'number'],
            [['record_type'], 'string'],
            [['name', 'slug', 'trading_name', 'seo_title', 'meta_description'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
            [['meta_text'], 'string', 'max' => 1000],
            [['restaurantcol'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'slug' => Yii::t('app', 'Slug'),
            'trading_name' => Yii::t('app', 'Trading Name'),
            'vat' => Yii::t('app', 'Vat'),
            'cook_time' => Yii::t('app', 'Cook Time'),
            'price_range' => Yii::t('app', 'Price Range'),
            'opening_day' => Yii::t('app', 'Opening Day'),
            'avg_prepare_time' => Yii::t('app', 'Avg Prepare Time'),
            'is_newest' => Yii::t('app', 'Is Newest'),
            'seo_title' => Yii::t('app', 'Seo Title'),
            'meta_text' => Yii::t('app', 'Meta Text'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'default_food_prep_time' => Yii::t('app', 'Default Food Prep Time'),
            'current_food_prep_time' => Yii::t('app', 'Current Food Prep Time'),
            'driver_delay_time' => Yii::t('app', 'Driver Delay Time'),
            'have_app' => Yii::t('app', 'Have App'),
            'is_featured' => Yii::t('app', 'Is Featured'),
            'is_from_signup' => Yii::t('app', 'Is From Signup'),
            'currency_id' => Yii::t('app', 'Currency ID'),
            'restaurantcol' => Yii::t('app', 'Restaurantcol'),
            'address_base_id' => Yii::t('app', 'Address Base ID'),
            'restaurant_group_id' => Yii::t('app', 'Restaurant Group ID'),
            'seo_area_id' => Yii::t('app', 'Seo Area ID'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomFieldValues()
    {
        return $this->hasMany(CustomFieldValue::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuAssignments()
    {
        return $this->hasMany(MenuAssignment::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyAssignments()
    {
        return $this->hasMany(PropertyAssignment::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddressBase()
    {
        return $this->hasOne(AddressBase::className(), ['id' => 'address_base_id']);
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
    public function getSeoArea()
    {
        return $this->hasOne(SeoArea::className(), ['id' => 'seo_area_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantAddresses()
    {
        return $this->hasMany(RestaurantAddress::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['id' => 'address_id'])->viaTable('restaurant_address', ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantBestForItems()
    {
        return $this->hasMany(RestaurantBestForItem::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantContacts()
    {
        return $this->hasMany(RestaurantContact::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantContactOrders()
    {
        return $this->hasMany(RestaurantContactOrder::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantCuisines()
    {
        return $this->hasMany(RestaurantCuisine::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDeliveries()
    {
        return $this->hasMany(RestaurantDelivery::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantLikes()
    {
        return $this->hasMany(RestaurantLike::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantPayments()
    {
        return $this->hasMany(RestaurantPayment::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantPhotos()
    {
        return $this->hasMany(RestaurantPhoto::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantSchedules()
    {
        return $this->hasMany(RestaurantSchedule::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSmsRecords()
    {
        return $this->hasMany(SmsRecord::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherRestaurants()
    {
        return $this->hasMany(VoucherRestaurant::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVouchers()
    {
        return $this->hasMany(Voucher::className(), ['id' => 'voucher_id'])->viaTable('voucher_restaurant', ['restaurant_id' => 'id']);
    }
}
