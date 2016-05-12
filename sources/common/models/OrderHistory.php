<?php

namespace common\models;

use \common\components\language\T;

/**
 * This is the model class for table "order_history".
 *
 * @property string $id
 * @property integer $order_id
 * @property string $user_id
 * @property string $status
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Order $order
 * @property UnstagedOrders $unstagedOrders
 */
class OrderHistory extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'user_id'], 'integer'],
            [['status', 'record_type'], 'string'],
            [['create_on', 'last_update'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => T::l('ID'),
            'order_id'    => T::l('Order ID'),
            'user_id'     => T::l('User ID'),
            'status'      => T::l('Status'),
            'record_type' => T::l('Record Type'),
            'create_on'   => T::l('Create On'),
            'last_update' => T::l('Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), [ 'id' => 'order_id' ]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), [ 'id' => 'user_id' ]);
    }
}
