<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_address".
 *
 * @property string $id
 * @property string $user_id
 * @property string $address_id
 * @property string $address_type
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Address $address
 * @property User $user
 */
class UserAddress extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'address_id'], 'required'],
            [['user_id', 'address_id'], 'integer'],
            [['address_type', 'record_type'], 'string'],
            [['create_on', 'last_update'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'user_id' => Yii::t('label', 'User ID'),
            'address_id' => Yii::t('label', 'Address ID'),
            'address_type' => Yii::t('label', 'Address Type'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public static function saveByPost($postedUser) {
        $address = Address::find()->where(['id' => $postedUser['address_id']])->with('country')->one();
        $userAddress = static::find()->where(['address_id' => $postedUser['address_id'], 'user_id' => $postedUser['user_id']])->one();
        
        if ($userAddress == null){
            $userAddress = new UserAddress();
            unset($postedUser['address_id']);
        }
       
        if ($address == null){
            $address = new Address();
            $country = Country::find()->where(['id' => $postedUser['address']['country_id']])->one();
            if (!empty($country)){
                $address->populateRelation('country', $country);
            }
        }
        
        if ($address->load($postedUser['address'],'') && $address->save()){
            $userAddress->load($postedUser,'');
            $userAddress->address_id = $address->id;
            if ($userAddress->save() && count($userAddress->errors) == 0){
                $userAddress->refresh();
                $userAddress->populateRelation('address', $address);
            }
        }
        
        return $userAddress;
    }
}
