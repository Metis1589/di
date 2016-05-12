<?php

namespace common\models;

use common\components\language\T;
use Yii;

/**
 * This is the model class for table "restaurant_contact_order".
 *
 * @property string $id
 * @property string $type
 * @property string $name
 * @property string $number
 * @property string $email
 * @property string $role
 * @property double $charge
 * @property integer $delay_in_min
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 * @property integer $restaurant_id
 *
 * @property Contact $contact
 * @property Restaurant $restaurant
 */
class RestaurantContactOrder extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_contact_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'restaurant_id'], 'required'],
            ['restaurant_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\Restaurant', 'targetAttribute' => 'id', 'message' => T::e('Invalid restaurant')],
            [['type', 'record_type', 'email'], 'string'],
            ['email', 'email'],
            [['charge'], 'number'],
            [['delay_in_min', 'restaurant_id'], 'integer'],
            [['number','name','role'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'type' => Yii::t('label', 'Type'),
            'charge' => Yii::t('label', 'Charge'),
            'delay_in_min' => Yii::t('label', 'Delay In Min'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
            'restaurant_id' => Yii::t('label', 'Restaurant Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }


    public static function saveByPost($postedContact, $restaurant_id) {
        $existedContact = static::findOne($postedContact['id']);
        if ($existedContact == null) {
            $existedContact = new RestaurantContactOrder();
        }
        $existedContact->load($postedContact,'');
        $existedContact->restaurant_id = $restaurant_id;
        if ($existedContact->save()) {
            return $existedContact;
        }
        return false;
    }
}
