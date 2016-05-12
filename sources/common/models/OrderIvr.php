<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_ivr".
 *
 * @property string $id
 * @property string $ivr_type
 * @property integer $order_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Order $order
 */
class OrderIvr extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_ivr';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ivr_type', 'order_id'], 'required'],
            [['ivr_type', 'record_type'], 'string'],
            [['order_id'], 'integer'],
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
            'ivr_type' => Yii::t('app', 'Ivr Type'),
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
