<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "label_language".
 *
 * @property string $language_id
 * @property string $label_id
 * @property string $value
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Language $language
 * @property Label $label
 */
class LabelLanguage extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'label_language';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id', 'label_id', 'value'], 'required'],
            [['language_id', 'label_id'], 'integer'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            //[['value'], 'string', 'max' => 500,  'message' => Yii::t('error', 'Translation is too long')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'language_id' => Yii::t('app', 'Language ID'),
            'label_id' => Yii::t('app', 'Label ID'),
            'value' => Yii::t('app', 'Value'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLabel()
    {
        return $this->hasOne(Label::className(), ['id' => 'label_id']);
    }
}
