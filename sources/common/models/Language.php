<?php

namespace common\models;

use Yii;
use common\enums\RecordType;

/**
 * This is the model class for table "language".
 *
 * @property string $id
 * @property string $name
 * @property string $iso_code
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property LabelLanguage[] $labelLanguages
 * @property Label[] $labels
 * @property Navigation[] $navigations
 * @property Page[] $pages
 */
class Language extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'language';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required', 'message' => Yii::t('error','Name is missing')],
            ['name', 'common\validators\CustomUniqueValidator', 'message' => Yii::t('error','Name is duplicated')],
            ['iso_code', 'common\validators\CustomUniqueValidator', 'message' => Yii::t('error','ISO Code is duplicated')],
            ['iso_code', 'required',  'message' => Yii::t('error','Iso Code is missing')],
            ['iso_code', 'match', 'pattern' => '/[A-Za-z]{2}/', 'message' => Yii::t('error','Iso Code is invalid')],
            ['record_type', 'required',  'message' => Yii::t('error','Record Type is missing')],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['name'], 'string', 'max' => 50, 'message' => Yii::t('error','Name is invalid')],
            [['iso_code'], 'string', 'max' => 2, 'message' => Yii::t('error','Iso Code is invalid')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'name' => Yii::t('label', 'Name'),
            'iso_code' => Yii::t('label', 'Iso Code'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLabelLanguages()
    {
        return $this->hasMany(LabelLanguage::className(), ['language_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLabels()
    {
        return $this->hasMany(Label::className(), ['id' => 'label_id'])->viaTable('label_language', ['language_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNavigations()
    {
        return $this->hasMany(Navigation::className(), ['language_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['language_id' => 'id']);
    }

}
