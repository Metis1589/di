<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "feedback".
 *
 * @property string $id
 * @property integer $restaurant_id
 * @property string $user_id
 * @property integer $order_id
 * @property string $title
 * @property string $text
 * @property integer $rating
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Order $order
 * @property Restaurant $restaurant
 * @property User $user
 */
class Feedback extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['restaurant_id', 'order_id', 'text', 'rating'], 'required'],
            [['restaurant_id', 'user_id', 'order_id', 'rating'], 'integer'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['text'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'restaurant_id' => Yii::t('app', 'Restaurant ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'title' => Yii::t('app', 'Title'),
            'text' => Yii::t('app', 'Text'),
            'rating' => Yii::t('app', 'Rating'),
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
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
