<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "label".
 *
 * @property string $id
 * @property string $client_id
 * @property string $code
 * @property string $description
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property LabelLanguage[] $labelLanguages
 * @property Language[] $languages
 */
class Label extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'label';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'description','record_type'], 'required'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['code'], 'string', 'max' => 190],
            [['description'], 'string', 'max' => 250],
            ['client_id', 'number'],
            [['code'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'client_id' => Yii::t('label', 'Client'),
            'code' => Yii::t('label', 'Code'),
            'description' => Yii::t('label', 'Description'),
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
        return $this->hasMany(LabelLanguage::className(), ['label_id' => 'id']);
    }

    public function getLabelLanguage()
    {
        $languageId = Yii::$app->globalCache->getLanguageId(Yii::$app->language);
        return $this->hasMany(LabelLanguage::className(), ['label_id' => 'id'])->andOnCondition('label_language.language_id ='. $languageId);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasMany(Language::className(), ['id' => 'language_id'])->viaTable('label_language', ['label_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    public function findLabelLanguage($isoCode) {
        foreach($this->labelLanguages as $labelLanguage) {
            if ($labelLanguage->language->iso_code == $isoCode) {
                return $labelLanguage;
            }
        }
        return null;
    }


//    public function beforeSave($inserted) {
//        if (Yii::$app->request->isImpersonated()) {
//            $this->client_id = Yii::$app->request->getImpersonatedClientId();
//        }
//        return parent::beforeSave($inserted);
//    }
}
