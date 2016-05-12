<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sms_record".
 *
 * @property string $id
 * @property string $mobile
 * @property string $message
 * @property string $send_date
 * @property double $cost
 * @property string $sms_type
 * @property integer $order_id
 * @property integer $restaurant_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Order $order
 * @property Restaurant $restaurant
 */
class SmsRecord extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile', 'message', 'cost', 'sms_type', 'order_id', 'restaurant_id'], 'required'],
            [['send_date', 'create_on', 'last_update'], 'safe'],
            [['cost'], 'number'],
            [['sms_type', 'record_type'], 'string'],
            [['order_id', 'restaurant_id'], 'integer'],
            [['mobile', 'message'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'mobile' => Yii::t('app', 'Mobile'),
            'message' => Yii::t('app', 'Message'),
            'send_date' => Yii::t('app', 'Send Date'),
            'cost' => Yii::t('app', 'Cost'),
            'sms_type' => Yii::t('app', 'Sms Type'),
            'order_id' => Yii::t('app', 'Order ID'),
            'restaurant_id' => Yii::t('app', 'Restaurant ID'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }
}
