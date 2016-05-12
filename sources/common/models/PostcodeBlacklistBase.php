<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "postcode_blacklist".
 *
 * @property string $id
 * @property integer $client_id
 * @property string $postcode_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Postcode $postcode
 * @property Client $client
 */
class PostcodeBlacklistBase extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'postcode_blacklist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'postcode_id'], 'required'],
            [['client_id', 'postcode_id'], 'integer'],
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
            'id' => Yii::t('label', 'ID'),
            'client_id' => Yii::t('label', 'Client ID'),
            'postcode_id' => Yii::t('label', 'Postcode ID'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostcode()
    {
        return $this->hasOne(Postcode::className(), ['id' => 'postcode_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }
}
