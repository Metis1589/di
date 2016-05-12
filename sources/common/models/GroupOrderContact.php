<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "group_order_contact".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $contact_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Order $order
 * @property Contact $contact
 * @property OrderItemGroupOrderContact[] $orderItemGroupOrderContacts
 * @property OrderItem[] $orderItems
 */
class GroupOrderContact extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'group_order_contact';
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
            'id' => Yii::t('app', 'ID'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItemGroupOrderContacts()
    {
        return $this->hasMany(OrderItemGroupOrderContact::className(), ['group_order_contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['id' => 'order_item_id'])->viaTable('order_item_group_order_contact', ['group_order_contact_id' => 'id']);
    }
}
