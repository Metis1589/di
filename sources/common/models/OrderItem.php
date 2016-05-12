<?php

namespace common\models;

use common\enums\RecordType;
use Yii;

/**
 * This is the model class for table "order_item".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $menu_item_id
 * @property double $web_price
 * @property double $restaurant_price
 * @property integer $is_alcohol
 * @property integer $quantity
 * @property double $web_total
 * @property double $restaurant_total
 * @property double $discount
 * @property double cook_time
 * @property string $special_instructions
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Order $order
 * @property MenuItem $menuItem
 * @property OrderItemGroupOrderContact[] $orderItemGroupOrderContacts
 * @property GroupOrderContact[] $groupOrderContacts
 * @property OrderOption[] $orderOptions
 */
class OrderItem extends \common\models\BaseModel
{
    public $name;
    public $options = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'menu_item_id', 'web_price', 'restaurant_price', 'quantity', 'web_total', 'restaurant_total'], 'required'],
            [['order_id', 'menu_item_id', 'quantity', 'is_alcohol'], 'integer'],
            [['web_price', 'restaurant_price', 'web_total', 'restaurant_total', 'discount'], 'number'],
            [['record_type'], 'string'],
            [['cook_time', 'create_on', 'last_update', 'special_instructions'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
           'id' => Yii::t('label', 'ID'),
            'order_id' => Yii::t('label', 'Order ID'),
            'menu_item_id' => Yii::t('label', 'Menu Item ID'),
            'web_price' => Yii::t('label', 'Web Price'),
            'restaurant_price' => Yii::t('label', 'Restaurant Price'),
            'is_alcohol' => Yii::t('label', 'Is Alcohol'),
            'quantity' => Yii::t('label', 'Quantity'),
            'web_total' => Yii::t('label', 'Web Total'),
            'restaurant_total' => Yii::t('label', 'Restaurant Total'),
            'cook_time' => Yii::t('label', 'Cook Time'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
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
    public function getMenuItem()
    {
        return $this->hasOne(MenuItem::className(), ['id' => 'menu_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItemGroupOrderContacts()
    {
        return $this->hasMany(OrderItemGroupOrderContact::className(), ['order_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupOrderContacts()
    {
        return $this->hasMany(GroupOrderContact::className(), ['id' => 'group_order_contact_id'])->viaTable('order_item_group_order_contact', ['order_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderOptions()
    {
        return $this->hasMany(OrderOption::className(), ['order_item_id' => 'id']);
    }

    public function getWebTotal($include_options_total, $include_quantity) {
        $result = $this->web_price * ($include_quantity ? $this->quantity : 1);

        if ($include_options_total) {
            foreach ($this->options as $option) {
                $result += $option->web_price * ($include_quantity ? $option->quantity : 1);;
            }
        }

        return $result;
    }

    public function getRestaurantTotal($include_options_total) {
        $result = $this->restaurant_price * $this->quantity;

        if ($include_options_total) {
            foreach ($this->options as $option) {
                $result += $option->restaurant_price * $option->quantity;;
            }
        }

        return $result;
    }

    /**
     * @param $field_key
     * @return string
     */
    public function getOrderItemCustomFieldValue($field_key) {
        $customFieldValue = CustomFieldValue::find()->joinWith('customField')->where([
            'custom_field.key' => $field_key,
            'custom_field_value.menu_item_id' => $this->menuItem->id,
            'client_id' => $this->order->restaurant->client_id,
            'custom_field.record_type' => RecordType::Active,
            'custom_field_value.record_type' => RecordType::Active
        ])->one();

        if (isset($customFieldValue)) {
            return $customFieldValue->value;
        }

        return null;
    }
}
