<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_option".
 *
 * @property string $id
 * @property integer $order_item_id
 * @property string $menu_option_id
 * @property double $web_price
 * @property double $restaurant_price
 * @property double $discount
 * @property integer $quantity
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property OrderItem $orderItem
 * @property MenuOption $menuOption
 */
class OrderOption extends \common\models\BaseModel
{
    public $name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_item_id', 'menu_option_id', 'quantity'], 'required'],
            [['order_item_id', 'menu_option_id', 'quantity'], 'integer'],
            [['web_price', 'restaurant_price', 'discount'], 'number'],
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
            'order_item_id' => Yii::t('app', 'Order Item ID'),
            'menu_option_id' => Yii::t('app', 'Menu Option ID'),
            'web_price' => Yii::t('app', 'Web Price'),
            'restaurant_price' => Yii::t('app', 'Restaurant Price'),
            'quantity' => Yii::t('app', 'Quantity'),
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
    public function getMenuOption()
    {
        return $this->hasOne(MenuOption::className(), ['id' => 'menu_option_id']);
    }
}
