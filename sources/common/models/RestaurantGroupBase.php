<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant_group".
 *
 * @property string $id
 * @property string $restaurant_chain_id
 * @property string $parent_id
 * @property string $name_key
 * @property string $currency_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property MenuAssignment[] $menuAssignments
 * @property PropertyAssignment[] $propertyAssignments
 * @property Restaurant[] $restaurants
 * @property Currency $currency
 * @property RestaurantChain $restaurantChain
 * @property RestaurantGroupBase $parent
 * @property RestaurantGroupBase[] $restaurantGroupBases
 * @property User[] $users
 * @property VoucherRestaurantGroup[] $voucherRestaurantGroups
 * @property Voucher[] $vouchers
 */
class RestaurantGroupBase extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['restaurant_chain_id', 'name_key', 'currency_id'], 'required'],
            [['restaurant_chain_id', 'parent_id', 'currency_id'], 'integer'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['name_key'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'restaurant_chain_id' => Yii::t('app', 'Restaurant Chain ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'name_key' => Yii::t('app', 'Name Key'),
            'currency_id' => Yii::t('app', 'Currency ID'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuAssignments()
    {
        return $this->hasMany(MenuAssignment::className(), ['restaurant_group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyAssignments()
    {
        return $this->hasMany(PropertyAssignment::className(), ['restaurant_group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants()
    {
        return $this->hasMany(Restaurant::className(), ['restaurant_group_id' => 'id']);
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
    public function getRestaurantChain()
    {
        return $this->hasOne(RestaurantChain::className(), ['id' => 'restaurant_chain_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(RestaurantGroupBase::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantGroupBases()
    {
        return $this->hasMany(RestaurantGroupBase::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['restaurant_group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherRestaurantGroups()
    {
        return $this->hasMany(VoucherRestaurantGroup::className(), ['restaurant_group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVouchers()
    {
        return $this->hasMany(Voucher::className(), ['id' => 'voucher_id'])->viaTable('voucher_restaurant_group', ['restaurant_group_id' => 'id']);
    }
}
