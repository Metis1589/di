<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant_chain_user".
 *
 * @property string $user_id
 * @property string $restaurant_chain_id
 * @property string $role
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property User $user
 * @property RestaurantChain $restaurantChain
 */
class RestaurantChainUser extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_chain_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'restaurant_chain_id'], 'required'],
            [['user_id', 'restaurant_chain_id'], 'integer'],
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
            'user_id' => Yii::t('app', 'User ID'),
            'restaurant_chain_id' => Yii::t('app', 'Restaurant Chain ID'),
            'role' => Yii::t('app', 'Role'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantChain()
    {
        return $this->hasOne(RestaurantChain::className(), ['id' => 'restaurant_chain_id']);
    }
}
