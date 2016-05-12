<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_payment".
 *
 * @property string $id
 * @property string $credit_card_type
 * @property double $commision_rate
 * @property double $payment_cost
 * @property string $payment_cost_type
 * @property string $customer_cost_type
 * @property double $customer_cost
 * @property double $payment_charge
 * @property double $total
 * @property double $restaurant_total
 * @property double $msd_cost
 * @property double $delivary_charge
 * @property double $refund_amount
 * @property double $restaurant_charge
 * @property double $restaurant_refund
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Order[] $orders
 */
class OrderPayment extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['credit_card_type', 'payment_cost', 'payment_cost_type', 'customer_cost_type'], 'required'],
            [['credit_card_type', 'payment_cost_type', 'customer_cost_type', 'record_type'], 'string'],
            [['commision_rate', 'payment_cost', 'customer_cost', 'payment_charge', 'total', 'restaurant_total', 'msd_cost', 'delivary_charge', 'refund_amount', 'restaurant_charge', 'restaurant_refund'], 'number'],
            [['create_on', 'last_update'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'credit_card_type' => Yii::t('app', 'Credit Card Type'),
            'commision_rate' => Yii::t('app', 'Commision Rate'),
            'payment_cost' => Yii::t('app', 'Payment Cost'),
            'payment_cost_type' => Yii::t('app', 'Payment Cost Type'),
            'customer_cost_type' => Yii::t('app', 'Customer Cost Type'),
            'customer_cost' => Yii::t('app', 'Customer Cost'),
            'payment_charge' => Yii::t('app', 'Payment Charge'),
            'total' => Yii::t('app', 'Total'),
            'restaurant_total' => Yii::t('app', 'Restaurant Total'),
            'msd_cost' => Yii::t('app', 'Msd Cost'),
            'delivary_charge' => Yii::t('app', 'Delivary Charge'),
            'refund_amount' => Yii::t('app', 'Refund Amount'),
            'restaurant_charge' => Yii::t('app', 'Restaurant Charge'),
            'restaurant_refund' => Yii::t('app', 'Restaurant Refund'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['order_payment_id' => 'id']);
    }
}
