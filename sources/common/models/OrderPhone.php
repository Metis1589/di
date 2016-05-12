<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_phone".
 *
 * @property integer $order_id
 * @property string $phone_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Order $order
 * @property Phone $phone
 */
class OrderPhone extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_phone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'phone_id'], 'required'],
            [['order_id', 'phone_id'], 'integer'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => Yii::t('app', 'Order ID'),
            'phone_id' => Yii::t('app', 'Phone ID'),
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
    public function getPhone()
    {
        return $this->hasOne(Phone::className(), ['id' => 'phone_id']);
    }
}
