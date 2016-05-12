<?php

namespace common\models;

use \common\components\language\T;

/**
 * This is the model class for table "order".
 *
 * @property string $id
 * @property integer $restaurant_id
 * @property string $restaurant_name
 * @property string $delivery_provider
 * @property double $sales_fee_value
 * @property string $sales_fee_type
 * @property string $sales_charge_type
 * @property double $collection_fee_value
 * @property string $collection_fee_type
 * @property string $collection_charge_type
 * @property double $vat_value
 * @property string $user_id
 * @property string $order_number
 * @property integer $is_amend
 * @property string $postcode
 * @property string $delivery_type
 * @property string $later_date_from
 * @property string $later_date_to
 * @property string $member_comment
 * @property string $restaurant_comment
 * @property string $internal_comment
 * @property string $delivery_address_data
 * @property string $billing_address_data
 * @property integer $is_utensils
 * @property string $status
 * @property integer $is_corporate
 * @property integer $is_term_cond
 * @property integer $is_term_cond_acc_pol
 * @property integer $is_subscribe_own
 * @property integer $is_subscribe_other
 * @property integer $is_in_dispatch
 * @property string $food_preparation_time
 * @property string $voucher_data
 * @property string $voucher_code
 * @property string $currency_code
 * @property string $currency_symbol
 * @property double $delivery_charge
 * @property double $driver_charge
 * @property double $subtotal
 * @property double $total
 * @property double $refund_amount
 * @property double $restaurant_subtotal
 * @property double $restaurant_total
 * @property double $restaurant_refund_amount
 * @property double $payment_charge
 * @property string $estimated_time
 * @property string $ready_by
 * @property double $client_refund
 * @property double $restaurant_charge
 * @property double $restaurant_refund
 * @property double $corporate_client_refund
 * @property double $corporate_restaurant_refund
 * @property double $client_cost
 * @property double $client_received
 * @property double $restaurant_credit
 * @property string $corp_expense_type_data
 * @property double $corp_total_allocated
 * @property string $corp_company_data
 * @property double $paid
 * @property integer $loyalty_points
 * @property string $cancellation_reason
 * @property string $auth_result
 * @property string $psp_reference
 * @property string $merchant_reference
 * @property string $skin_code
 * @property string $merchant_sig
 * @property string $payment_method
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property CorporateOrder[] $corporateOrders
 * @property Feedback[] $feedbacks
 * @property GroupOrderContact[] $groupOrderContacts
 * @property Restaurant $restaurant
 * @property User $user
 * @property OrderContactHistory[] $orderContactHistories
 * @property OrderHistory[] $orderHistories
 * @property OrderItem[] $orderItems
 * @property OrderTimeTrack[] $orderTimeTracks
 */
class ReportOrder extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['restaurant_id', 'postcode', 'delivery_type', 'currency_code', 'delivery_charge', 'subtotal', 'total', 'restaurant_subtotal', 'restaurant_total', 'estimated_time'], 'required'],
            [['restaurant_id', 'user_id', 'is_amend', 'is_utensils', 'is_corporate', 'is_term_cond', 'is_term_cond_acc_pol', 'is_subscribe_own', 'is_subscribe_other', 'is_in_dispatch', 'loyalty_points'], 'integer'],
            [['delivery_type', 'delivery_address_data', 'billing_address_data', 'status', 'voucher_data', 'voucher_code', 'delivery_provider', 'record_type'], 'string'],
            [['later_date_from', 'later_date_to', 'food_preparation_time', 'estimated_time', 'create_on', 'last_update'], 'safe'],
            [['delivery_charge', 'driver_charge', 'subtotal', 'total', 'refund_amount', 'restaurant_subtotal', 'restaurant_total', 'restaurant_refund_amount', 'payment_charge', 'client_refund', 'restaurant_charge', 'restaurant_refund', 'corporate_client_refund', 'corporate_restaurant_refund', 'client_cost', 'client_received', 'restaurant_credit'], 'number'],
            [['order_number'], 'string', 'max' => 50],
            [['postcode'], 'string', 'max' => 45],
            [['member_comment', 'restaurant_comment', 'internal_comment'], 'string', 'max' => 500],
            [['currency_code'], 'string', 'max' => 10],
            [['auth_result', 'psp_reference', 'skin_code', 'payment_method'], 'string', 'max' => 100], 
            [['merchant_reference', 'merchant_sig'], 'string', 'max' => 250], 
            [['order_number'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                          => T::l('ID'),
            'restaurant_id'               => T::l('Restaurant ID'),
            'user_id'                     => T::l('User ID'),
            'order_number'                => T::l('Order Number'),
            'is_amend'                    => T::l('Is Amend'),
            'postcode'                    => T::l('Postcode'),
            'delivery_type'               => T::l('Delivery Type'),
            'later_date_from'             => T::l('Later Date From'),
            'later_date_to'               => T::l('Later Date To'),
            'member_comment'              => T::l('Member Comment'),
            'restaurant_comment'          => T::l('Restaurant Comment'),
            'internal_comment'            => T::l('Internal Comment'),
            'delivery_address_data'       => T::l('Delivery Address Data'),
            'billing_address_data'        => T::l('Billing Address Data'),
            'is_utensils'                 => T::l('Is Utensils'),
            'status'                      => T::l('Status'),
            'is_corporate'                => T::l('Is Corporate'),
            'is_term_cond'                => T::l('Is Term Cond'),
            'is_term_cond_acc_pol'        => T::l('Is Term Cond Acc Pol'),
            'is_subscribe_own'            => T::l('Is Subscribe Own'),
            'is_subscribe_other'          => T::l('Is Subscribe Other'),
            'is_in_dispatch'              => T::l('Is In Dispatch'),
            'food_preparation_time'       => T::l('Food Preparation Time'),
            'voucher_data'                => T::l('Voucher Data'),
            'voucher_code'                => T::l('Voucher Data'),
            'currency_code'               => T::l('Currency Code'),
            'delivery_charge'             => T::l('Delivery Charge'),
            'driver_charge'               => T::l('Driver Charge'),
            'delivery_provider'           => T::l('Delivery Provider'),
            'subtotal'                    => T::l('Subtotal'),
            'total'                       => T::l('Total'),
            'refund_amount'               => T::l('Refund Amount'),
            'restaurant_subtotal'         => T::l('Restaurant Subtotal'),
            'restaurant_total'            => T::l('Restaurant Total'),
            'restaurant_refund_amount'    => T::l('Restaurant Refund Amount'),
            'payment_charge'              => T::l('Payment Charge'),
            'estimated_time'              => T::l('Estimated Time'),
            'client_refund'               => T::l('Client Refund'),
            'restaurant_charge'           => T::l('Restaurant Charge'),
            'restaurant_refund'           => T::l('Restaurant Refund'),
            'corporate_client_refund'     => T::l('Corporate Client Refund'),
            'corporate_restaurant_refund' => T::l('Corporate Restaurant Refund'),
            'client_cost'                 => T::l('Client Cost'),
            'client_received'             => T::l('Client Received'),
            'restaurant_credit'           => T::l('Restaurant Credit'),
            'corp_expense_type_data'      => T::l('Corp Expense Type Data'),
            'corp_total_allocated'        => T::l('Corp Total Allocated'),
            'paid'                        => T::l('Paid'),
            'loyalty_points'              => T::l('Loyalty Points'),
            'auth_result'                 => T::l('Auth Result'),
            'psp_reference'               => T::l('Psp Reference'),
            'merchant_reference'          => T::l('Merchant Reference'),
            'skin_code'                   => T::l('Skin Code'),
            'merchant_sig'                => T::l('Merchant Sig'),
            'payment_method'              => T::l('Payment Method'),
            'record_type'                 => T::l('Record Type'),
            'create_on'                   => T::l('Create On'),
            'last_update'                 => T::l('Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCorporateOrders()
    {
        return $this->hasMany(CorporateOrder::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupOrderContacts()
    {
        return $this->hasMany(GroupOrderContact::className(), ['order_id' => 'id']);
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderHistories()
    {
        return $this->hasMany(OrderHistory::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderIvrs()
    {
        return $this->hasMany(OrderIvr::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderIvrHistories()
    {
        return $this->hasMany(OrderIvrHistory::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderTimeTracks()
    {
        return $this->hasMany(OrderTimeTrack::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDelivery()
    {
        return $this->hasOne(RestaurantDelivery::className(), ['restaurant_id' => 'id'])->via('restaurant');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderContactHistories()
    {
        return $this->hasMany(OrderContactHistory::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantContacts()
    {
        return $this->hasMany(RestaurantContactOrder::className(), ['restaurant_id' => 'id'])->via('restaurant');
    }

    /**
     * Copy from order
     * @param $order
     */
    public static function copyFromOrder($order)
    {
        $reportOrder = static::findOne(['id' => $order->id]);

        if ($reportOrder == null) {
            $reportOrder = new ReportOrder();
        }

        $reportOrder->setAttributes($order->attributes, false);

        $reportOrder->save();
    }
}
