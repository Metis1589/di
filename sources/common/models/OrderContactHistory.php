<?php

namespace common\models;

use \common\components\language\T;

/**
 * This is the model class for table "order_contact_history".
 *
 * @property string $id
 * @property string $type
 * @property string $name
 * @property string $number
 * @property string $email
 * @property string $role
 * @property double $charge
 * @property string $status
 * @property string $order_status
 * @property integer $delay_in_min
 * @property integer $is_succeeded
 * @property integer $order_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Order $order
 */
class OrderContactHistory extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_contact_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name', 'role', 'order_id'], 'required'],
            [['type', 'record_type'], 'string'],
            ['charge', 'number'],
            [['status','order_status'], 'string'],
            [['delay_in_min', 'is_succeeded', 'order_id'], 'integer'],
            [['create_on', 'last_update'], 'safe'],
            [['name', 'role'], 'string', 'max' => 255],
            ['number', 'string', 'max' => 50],
            ['email',  'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => T::l('ID'),
            'type'         => T::l('Type'),
            'name'         => T::l('Name'),
            'number'       => T::l('Number'),
            'email'        => T::l('Email'),
            'role'         => T::l('Role'),
            'status'       => T::l('Status'),
            'order_status' => T::l('Order status'),
            'charge'       => T::l('Charge'),
            'delay_in_min' => T::l('Delay In Min'),
            'is_succeeded' => T::l('Is Succeeded'),
            'order_id'     => T::l('Order ID'),
            'record_type'  => T::l('Record Type'),
            'create_on'    => T::l('Create On'),
            'last_update'  => T::l('Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * Add order contact history record
     *
     * @param integer $orderId
     * @param string  $orderType
     * @param string  $status
     * @param string  $name
     * @param string  $to
     * @param string  $sid
     * @param integer $is_succeeded
     * @param string  $role
     * @param integer $duration
     * @param double  $price
     * @param string  $price_unit
     */
    public static function addRecord($orderId, $orderType, $status, $name = 'Unauthorized', $to, $sid, $is_succeeded = 1, $role = 'Unauthorized', $duration = 0, $price = 0, $price_unit = 'usd')
    {
        $history               = new \common\models\OrderContactHistory;
        $history->type         = $orderType;
        $history->name         = $name;
        $history->number       = $to;
        $history->role         = $role;
        $history->order_id     = $orderId;
        $history->duration     = $duration;
        $history->price        = $price;
        $history->sid          = $sid;
        $history->status       = $status;
        $history->price_unit   = $price_unit;
        $history->is_succeeded = $is_succeeded;
        $history->record_type  = \common\enums\RecordType::Active;
        $history->save();
    }
}
