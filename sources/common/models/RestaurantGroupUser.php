<?php

namespace common\models;

use Yii;
use common\enums\RecordType;

/**
 * This is the model class for table "restaurant_group_user".
 *
 * @property string $user_id
 * @property string $restaurant_group_id
 * @property string $role
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property User $user
 * @property RestaurantGroup $restaurantGroup
 */
class RestaurantGroupUser extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_group_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['user_id', 'unique', 'targetAttribute' => ['user_id', 'restaurant_group_id'], 'filter' => "record_type <> 'Deleted'",  'message' => Yii::t('error', 'FIELDS ARE NOT UNIQUE')],
            ['user_id', 'required', 'message' => Yii::t('error', 'User is missing')],
            ['restaurant_group_id', 'required', 'message' => Yii::t('error', 'Restaurant group is missing')],
            ['restaurant_group_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\RestaurantGroup', 'targetAttribute' => 'id',  'message' => Yii::t('label', 'Invalid Restaurant Group')],
            ['user_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\User', 'targetAttribute' => 'id',  'message' => Yii::t('label', 'Invalid User')],
            ['record_type', 'required', 'message' => Yii::t('error', 'Record Type is missing')],
            [['user_id', 'restaurant_group_id'], 'integer'],
            [['create_on', 'last_update'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'id'),
            'user_id' => Yii::t('label', 'User'),
            'restaurant_group_id' => Yii::t('label', 'Restaurant Group'),
            'role' => Yii::t('label', 'Role'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
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
    public function getRestaurantGroup()
    {
        return $this->hasOne(RestaurantGroup::className(), ['id' => 'restaurant_group_id']);
    }
    
    public function getAdminUsers()
    {
        return yii\helpers\ArrayHelper::map(\yii\web\User::find()->where("record_type <> '".RecordType::Deleted."'")->all(), 'id', 'username');
    }
}
