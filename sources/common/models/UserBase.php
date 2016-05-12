<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property integer $client_id
 * @property string $company_id
 * @property string $restaurant_chain_id
 * @property string $restaurant_group_id
 * @property integer $company_user_group_id
 * @property integer $restaurant_id
 * @property string $username
 * @property string $password
 * @property string $user_type
 * @property string $first_name
 * @property string $last_name
 * @property string $title
 * @property string $last_visit
 * @property string $activation_hash
 * @property string $photo
 * @property string $dob
 * @property string $know_about
 * @property integer $term_and_cond
 * @property integer $term_and_cond_web
 * @property integer $term_and_cond_acc_pol
 * @property integer $is_corporate_approved
 * @property string $api_token
 * @property string $reset_password_hash
 * @property string $affiliate_id
 * @property integer $loyalty_points
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Feedback[] $feedbacks
 * @property Order[] $orders
 * @property Client $client
 * @property Company $company
 * @property CompanyUserGroup $companyUserGroup
 * @property Restaurant $restaurant
 * @property RestaurantChain $restaurantChain
 * @property UserAddress[] $userAddresses
 * @property Voucher[] $vouchers
 */
class UserBase extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'company_id', 'restaurant_chain_id', 'restaurant_group_id', 'company_user_group_id', 'restaurant_id', 'term_and_cond', 'term_and_cond_web', 'term_and_cond_acc_pol', 'is_corporate_approved', 'affiliate_id', 'loyalty_points'], 'integer'],
            [['username', 'password', 'user_type'], 'required'],
            [['user_type', 'record_type'], 'string'],
            [['last_visit', 'dob', 'create_on', 'last_update'], 'safe'],
            [['username', 'password', 'first_name', 'last_name', 'activation_hash', 'photo', 'know_about', 'reset_password_hash'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 45],
            [['api_token'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'client_id' => Yii::t('app', 'Client ID'),
            'company_id' => Yii::t('app', 'Company ID'),
            'restaurant_chain_id' => Yii::t('app', 'Restaurant Chain ID'),
            'restaurant_group_id' => Yii::t('app', 'Restaurant Group ID'),
            'company_user_group_id' => Yii::t('app', 'Company User Group ID'),
            'restaurant_id' => Yii::t('app', 'Restaurant ID'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'user_type' => Yii::t('app', 'User Type'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'title' => Yii::t('app', 'Title'),
            'last_visit' => Yii::t('app', 'Last Visit'),
            'activation_hash' => Yii::t('app', 'Activation Hash'),
            'photo' => Yii::t('app', 'Photo'),
            'dob' => Yii::t('app', 'Dob'),
            'know_about' => Yii::t('app', 'Know About'),
            'term_and_cond' => Yii::t('app', 'Term And Cond'),
            'term_and_cond_web' => Yii::t('app', 'Term And Cond Web'),
            'term_and_cond_acc_pol' => Yii::t('app', 'Term And Cond Acc Pol'),
            'is_corporate_approved' => Yii::t('app', 'Is Corporate Approved'),
            'api_token' => Yii::t('app', 'Api Token'),
            'reset_password_hash' => Yii::t('app', 'Reset Password Hash'),
            'affiliate_id' => Yii::t('app', 'Affiliate ID'),
            'loyalty_points' => Yii::t('app', 'Loyalty Points'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['user_id' => 'id']);
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
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyUserGroup()
    {
        return $this->hasOne(CompanyUserGroup::className(), ['id' => 'company_user_group_id']);
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
    public function getRestaurantChain()
    {
        return $this->hasOne(RestaurantChain::className(), ['id' => 'restaurant_chain_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAddresses()
    {
        return $this->hasMany(UserAddress::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVouchers()
    {
        return $this->hasMany(Voucher::className(), ['user_id' => 'id']);
    }
}
