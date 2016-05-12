<?php

namespace common\models;

use Yii;
use common\components\language\T;

/**
 * This is the model class for table "restaurant_contact".
 *
 * @property integer $id
 * @property integer $restaurant_id
 * @property integer $contact_id
 * @property string $role
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Restaurant $restaurant
 * @property Contact $contact
 */
class RestaurantContact extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['first_name', 'required','message' => T::e('First name is missing')],
            ['last_name', 'required','message' => T::e('Last name is missing')],
            ['number', 'required','message' => T::e('Number is missing')],
            ['contact_role', 'required','message' => T::e('Contact role is missing')],
            [['first_name'], 'string', 'max' => 50, 'tooLong'=>T::e('First name is too long')],
            [['last_name'], 'string', 'max' => 50, 'tooLong'=>T::e('Last name is too long')],
            [['number'], 'string', 'max' => 50, 'tooLong'=>T::e('Number is too long')],
            [['contact_role'], 'string', 'max' => 50, 'tooLong'=>T::e('Contact role is too long')],
            [['restaurant_id'], 'integer'],
            [['role', 'record_type','number','contact_role'], 'string'],
            [['create_on', 'last_update'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'restaurant_id' => Yii::t('label', 'Restaurant'),
            'role' => Yii::t('label', 'Role'),
            'number' => Yii::t('label', 'Phone'),
            'email' => Yii::t('label', 'Email'),
            'contact_role' => Yii::t('label', 'Role'),
            'first_name' => Yii::t('label', 'First name'),
            'last_name' => Yii::t('label', 'Last name'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
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
    /*
    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id' => 'contact_id']);
    }
     */
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmails()
    {
        return $this->hasMany(RestaurantContactEmail::className(), ['restaurant_contact_id' => 'id'])->andOnCondition(['restaurant_contact_email.record_type' => \common\enums\RecordType::Active]);
    }
   
}
