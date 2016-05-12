<?php

namespace common\models;

use common\enums\RecordType;
use Yii;

/**
 * This is the model class for table "restaurant_user".
 *
 * @property integer $restaurant_id
 * @property string $user_id
 * @property string $role
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Restaurant $restaurant
 * @property User $user
 */
class RestaurantUser extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['restaurant_id', 'user_id', 'record_type'], 'required'],
            [['restaurant_id', 'user_id'], 'integer'],
            [['restaurant_id'], 'unique', 'targetAttribute' => ['restaurant_id', 'user_id'], 'filter' => "record_type <> '".RecordType::Deleted."'", 'message' => Yii::t('label', 'This combination has been already taken')],
            ['restaurant_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\Restaurant', 'targetAttribute' => 'id',  'message' => Yii::t('label', 'Invalid restaurant')],
            ['user_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\User', 'targetAttribute' => 'id',  'message' => Yii::t('label', 'Invalid user')],
            [['role', 'record_type'], 'string'],
            [['create_on', 'last_update'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'restaurant_id' => Yii::t('app', 'Restaurant ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'role' => Yii::t('app', 'Role'),
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
