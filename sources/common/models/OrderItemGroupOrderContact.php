<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_item_group_order_contact".
 *
 * @property integer $order_item_id
 * @property integer $group_order_contact_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property OrderItem $orderItem
 * @property GroupOrderContact $groupOrderContact
 */
class OrderItemGroupOrderContact extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_item_group_order_contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_item_id', 'group_order_contact_id'], 'required'],
            [['order_item_id', 'group_order_contact_id'], 'integer'],
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
            'order_item_id' => Yii::t('app', 'Order Item ID'),
            'group_order_contact_id' => Yii::t('app', 'Group Order Contact ID'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItem()
    {
        return $this->hasOne(OrderItem::className(), ['id' => 'order_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupOrderContact()
    {
        return $this->hasOne(GroupOrderContact::className(), ['id' => 'group_order_contact_id']);
    }
}
