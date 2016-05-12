<?php

namespace common\models;
use common\components\language\T;

use Yii;

/**
 * This is the model class for table "voucher".
 *
 * @property string $id
 * @property integer $client_id
 * @property integer $restaurant_id
 * @property string $restaurant_chain_id
 * @property string $restaurant_group_id
 * @property string $user_id
 * @property string $validation_service
 * @property string $code
 * @property integer $code_min_length
 * @property integer $code_max_length
 * @property string $category
 * @property double $discount_value
 * @property string $discount_type
 * @property string $promotion_type
 * @property string $start_date
 * @property string $end_date
 * @property string $value_type
 * @property double $price_value
 * @property integer $item_quantity
 * @property string $description
 * @property integer $max_times_per_user
 * @property string $generate_by
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Client[] $clients
 * @property Client $client
 * @property MenuItem $menuItem
 * @property Restaurant $restaurant
 * @property RestaurantChain $restaurantChain
 * @property RestaurantGroup $restaurantGroup
 * @property User $user
 * @property VoucherMenuCategory[] $voucherMenuCategories
 * @property VoucherMenuItem[] $voucherMenuItems
 * @property VoucherSchedule[] $voucherSchedules
 * @property VoucherUseHistory[] $voucherUseHistories
 */
class VoucherBase extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'voucher';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'client_id' => Yii::t('app', 'Client ID'),
            'restaurant_id' => Yii::t('app', 'Restaurant ID'),
            'restaurant_chain_id' => Yii::t('app', 'Restaurant Chain ID'),
            'restaurant_group_id' => Yii::t('app', 'Restaurant Group ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'validation_service' => Yii::t('app', 'Validation Service'),
            'code' => Yii::t('app', 'Code'),
            'code_min_length' => Yii::t('app', 'Code Min Length'),
            'code_max_length' => Yii::t('app', 'Code Max Length'),
            'category' => Yii::t('app', 'Category'),
            'discount_value' => Yii::t('app', 'Discount Value'),
            'discount_type' => Yii::t('app', 'Discount Type'),
            'promotion_type' => Yii::t('app', 'Promotion Type'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'value_type' => Yii::t('app', 'Value Type'),
            'price_value' => Yii::t('app', 'Price Value'),
            'item_quantity' => Yii::t('app', 'Item Quantity'),
            'description' => Yii::t('app', 'Description'),
            'max_times_per_user' => Yii::t('app', 'Max Times Per User'),
            'generate_by' => Yii::t('app', 'Generate By'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClients()
    {
        return $this->hasMany(Client::className(), ['voucher_id' => 'id']);
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
    public function getRestaurantGroup()
    {
        return $this->hasOne(RestaurantGroup::className(), ['id' => 'restaurant_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherMenuCategories()
    {
        return $this->hasMany(VoucherMenuCategory::className(), ['voucher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherMenuItems()
    {
        return $this->hasMany(VoucherMenuItem::className(), ['voucher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherSchedules()
    {
        return $this->hasMany(VoucherSchedule::className(), ['voucher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherUseHistories()
    {
        return $this->hasMany(VoucherUseHistory::className(), ['voucher_id' => 'id']);
    }
}
