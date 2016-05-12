<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_time_track".
 *
 * @property string $id
 * @property integer $order_id
 * @property string $type
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Order $order
 */
class OrderTimeTrack extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_time_track';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'type'], 'required'],
            [['order_id'], 'integer'],
            [['type', 'record_type'], 'string'],
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
            'order_id' => Yii::t('app', 'Order ID'),
            'type' => Yii::t('app', 'Type'),
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
