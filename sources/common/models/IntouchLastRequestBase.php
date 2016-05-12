<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "intouch_last_request".
 *
 * @property string $id
 * @property string $type
 * @property integer $client_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Client $client
 */
class IntouchLastRequestBase extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'intouch_last_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'client_id'], 'required'],
            [['client_id'], 'integer'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['type'], 'string', 'max' => 190]
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
            'client_id' => Yii::t('label', 'Client ID'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }
}
