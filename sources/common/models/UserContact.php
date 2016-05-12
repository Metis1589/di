<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_contact".
 *
 * @property string $user_id
 * @property integer $contact_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property User $user
 * @property Contact $contact
 */
class UserContact extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'contact_id'], 'required'],
            [['user_id', 'contact_id'], 'integer'],
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
            'user_id' => Yii::t('app', 'User ID'),
            'contact_id' => Yii::t('app', 'Contact ID'),
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
    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id' => 'contact_id']);
    }
}
