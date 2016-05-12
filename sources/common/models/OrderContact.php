<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_contact".
 *
 * @property integer $order_id
 * @property integer $contact_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Order $order
 * @property Contact $contact
 */
class OrderContact extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'contact_id'], 'required'],
            [['order_id', 'contact_id'], 'integer'],
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
            'contact_id' => Yii::t('app', 'Contact ID'),
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
    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id' => 'contact_id']);
    }
}
