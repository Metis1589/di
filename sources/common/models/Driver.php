<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "driver".
 *
 * @property string $id
 * @property string $dispatch_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Order[] $orders
 */
class Driver extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'driver';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dispatch_id'], 'required'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['dispatch_id'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'dispatch_id' => Yii::t('app', 'Dispatch ID'),
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
        return $this->hasMany(Order::className(), ['driver_id' => 'id']);
    }
}
