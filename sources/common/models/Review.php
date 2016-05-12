<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "review".
 *
 * @property string $id
 * @property string $comment
 * @property string $title
 * @property integer $restaurant_id
 * @property integer $order_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Restaurant $restaurant
 * @property Order $order
 */
class Review extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'review';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment', 'record_type'], 'string'],
            [['restaurant_id', 'order_id'], 'required'],
            [['restaurant_id', 'order_id'], 'integer'],
            [['create_on', 'last_update'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'comment' => Yii::t('app', 'Comment'),
            'title' => Yii::t('app', 'Title'),
            'restaurant_id' => Yii::t('app', 'Restaurant ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
