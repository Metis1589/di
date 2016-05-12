<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "custom_field".
 *
 * @property string $id
 * @property integer $client_id
 * @property string $key
 * @property string $default_value
 * @property string $value_type
 * @property string $type
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Client $client
 * @property CustomFieldValue[] $customFieldValues
 */
class CustomFieldBase extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'custom_field';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'key', 'type', 'record_type'], 'required'],
            [['client_id'], 'integer'],
            [['value_type', 'type', 'record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['key'], 'string', 'max' => 45],
            [['default_value'], 'string', 'max' => 255]
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
            'key' => Yii::t('label', 'Key'),
            'default_value' => Yii::t('label', 'Default Value'),
            'value_type' => Yii::t('label', 'Value Type'),
            'type' => Yii::t('label', 'Type'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomFieldValues()
    {
        return $this->hasMany(CustomFieldValue::className(), ['custom_field_id' => 'id']);
    }
}
