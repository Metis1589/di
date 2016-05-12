<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "contact".
 *
 * @property integer $id
 * @property string $email
 * @property boolean $is_opt_in
 * @property string $phone_id
 * @property string $address_id
 * @property string $person_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property ClientContact[] $clientContacts
 * @property Client[] $clients
 * @property CompanyContact[] $companyContacts
 * @property Company[] $companies
 * @property Person $person
 * @property Phone $phone
 * @property Address $address
 * @property GroupOrderContact[] $groupOrderContacts
 * @property Order[] $orders
 * @property OrderContact[] $orderContacts
 * @property RestaurantContact[] $restaurantContacts
 * @property Restaurant[] $restaurants
 * @property RestaurantContactOrder[] $restaurantContactOrders
 * @property RestaurantSuggested[] $restaurantSuggesteds
 * @property UserContact[] $userContacts
 * @property User[] $users
 */
class Contact extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_opt_in'], 'boolean'],
            [['phone_id', 'address_id', 'person_id'], 'integer'],
            [['person_id'], 'required'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['email'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'is_opt_in' => Yii::t('app', 'Is Opt In'),
            'phone_id' => Yii::t('app', 'Phone ID'),
            'address_id' => Yii::t('app', 'Address ID'),
            'person_id' => Yii::t('app', 'Person ID'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientContacts()
    {
        return $this->hasMany(ClientContact::className(), ['contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClients()
    {
        return $this->hasMany(Client::className(), ['id' => 'client_id'])->viaTable('client_contact', ['contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyContacts()
    {
        return $this->hasMany(CompanyContact::className(), ['contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies()
    {
        return $this->hasMany(Company::className(), ['id' => 'company_id'])->viaTable('company_contact', ['contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhone()
    {
        return $this->hasOne(Phone::className(), ['id' => 'phone_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupOrderContacts()
    {
        return $this->hasMany(GroupOrderContact::className(), ['contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['id' => 'order_id'])->viaTable('order_contact', ['contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderContacts()
    {
        return $this->hasMany(OrderContact::className(), ['contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantContacts()
    {
        return $this->hasMany(RestaurantContact::className(), ['contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants()
    {
        return $this->hasMany(Restaurant::className(), ['id' => 'restaurant_id'])->viaTable('restaurant_contact', ['contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantContactOrders()
    {
        return $this->hasMany(RestaurantContactOrder::className(), ['contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantSuggesteds()
    {
        return $this->hasMany(RestaurantSuggested::className(), ['contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserContacts()
    {
        return $this->hasMany(UserContact::className(), ['contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_contact', ['contact_id' => 'id']);
    }
}
