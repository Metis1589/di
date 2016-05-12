<?php

namespace common\models;

use common\components\language\T;
use Yii;

/**
 * This is the model class for table "restaurant_contact_email".
 *
 * @property string $id
 * @property string $email
 * @property string $restaurant_contact_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property RestaurantContact $restaurantContact
 */
class RestaurantContactEmail extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_contact_email';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required','message' => T::e('Email is missing')],
            [['restaurant_contact_id'], 'integer'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['email'], 'string', 'max' => 150, 'tooLong'=>T::e('Email is too long')],
            [['email'], 'email','message' => T::e('Email is not a valid email address')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => T::l('Email'),
            'restaurant_contact_id' => T::l('Restaurant Contact ID'),
            'record_type' => T::l('Record Type'),
            'create_on' => T::l('Create On'),
            'last_update' => T::l('Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantContact()
    {
        return $this->hasOne(RestaurantContact::className(), ['id' => 'restaurant_contact_id']);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        Yii::$app->mailchimp->addUserToRestaurantList($this->restaurantContact->restaurant->client->key, $this->email, $this->restaurantContact->restaurant->name);
    }
}
