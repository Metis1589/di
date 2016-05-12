<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_rule".
 *
 * @property integer $id
 * @property integer $client_id
 * @property string $custom_field_id
 * @property string $delivery_type
 * @property string $value
 * @property string $message_key
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Client $client
 * @property CustomField $customField
 */
class OrderRuleBase extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'custom_field_id'], 'required'],
            [['client_id', 'custom_field_id'], 'integer'],
            [['delivery_type', 'record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['value', 'message_key'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'client_id' => Yii::t('app', 'Client ID'),
            'custom_field_id' => Yii::t('app', 'Custom Field ID'),
            'delivery_type' => Yii::t('app', 'Delivery Type'),
            'value' => Yii::t('app', 'Value'),
            'message_key' => Yii::t('app', 'Message Key'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomField()
    {
        return $this->hasOne(CustomField::className(), ['id' => 'custom_field_id']);
    }
}
