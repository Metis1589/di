<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "default_delivery_charges".
 *
 * @property string $id
 * @property double $mile
 * @property double $charge
 * @property integer $client_id
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Client $client
 */
class DefaultDeliveryCharges extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'default_delivery_charges';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mile', 'charge'], 'number'],
            [['client_id'], 'required'],
            [['client_id'], 'integer'],
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
            'id' => Yii::t('app', 'ID'),
            'mile' => Yii::t('app', 'Mile'),
            'charge' => Yii::t('app', 'Charge'),
            'client_id' => Yii::t('app', 'Client ID'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
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
