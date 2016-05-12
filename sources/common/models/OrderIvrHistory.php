<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_ivr_history".
 *
 * @property string $id
 * @property string $call_status
 * @property string $callsid
 * @property string $phone_number
 * @property double $duration
 * @property double $unit_price
 * @property double $twillo_call_cost
 * @property string $twillo_cost_unit
 * @property integer $order_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Order $order
 */
class OrderIvrHistory extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_ivr_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['call_status', 'callsid', 'phone_number', 'duration', 'unit_price', 'twillo_call_cost', 'twillo_cost_unit', 'order_id'], 'required'],
            [['call_status', 'twillo_cost_unit', 'record_type'], 'string'],
            [['duration', 'unit_price', 'twillo_call_cost'], 'number'],
            [['order_id'], 'integer'],
            [['create_on', 'last_update'], 'safe'],
            [['callsid', 'phone_number'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'call_status' => Yii::t('app', 'Call Status'),
            'callsid' => Yii::t('app', 'Callsid'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'duration' => Yii::t('app', 'Duration'),
            'unit_price' => Yii::t('app', 'Unit Price'),
            'twillo_call_cost' => Yii::t('app', 'Twillo Call Cost'),
            'twillo_cost_unit' => Yii::t('app', 'Twillo Cost Unit'),
            'order_id' => Yii::t('app', 'Order ID'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
