<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "client_contact".
 *
 * @property integer $client_id
 * @property integer $contact_id
 *
 * @property Client $client
 * @property Contact $contact
 */
class ClientContact extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client_contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'contact_id'], 'required'],
            [['client_id', 'contact_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'client_id' => Yii::t('app', 'Client ID'),
            'contact_id' => Yii::t('app', 'Contact ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id' => 'contact_id']);
    }
}
