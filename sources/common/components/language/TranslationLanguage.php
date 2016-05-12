<?php
namespace common\components\language;

use common\enums\RecordType;
use common\models\Label;
use common\models\LabelLanguage;
use Yii;
use yii\base\Component;

class TranslationLanguage extends Component
{
    const cookieKey = 'language';

    public $language = 'en';

    public function init() {
        parent::init();
        if (isset(Yii::$app->request->cookies)) {
            if (isset(Yii::$app->request->cookies[self::cookieKey])) {
                   $this->language = Yii::$app->request->cookies['language']->value;
            }
        }
    }

    public function set($language){
        Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => self::cookieKey,
            'value' => $language
        ]));
        Yii::$app->language = $language;
    }

    public function saveModel($model, $clientId) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($this->saveModelWithoutTransaction($model, $clientId)) {
                $transaction->commit();
                return true;
            } else {
                $transaction->rollBack();
                return false;
            }

        } catch (Exception $ex) {
            $transaction->rollBack();
            return false;
        }
    }

    public function saveModelWithoutTransaction($model, $clientId)
    {
        $isSaved = true;
        $languageId = Yii::$app->globalCache->getLanguageId($this->language);
        $properties = $model->translatedProperties();
        $oldAttributes = $model->oldAttributes;
        $isNewRecord = $model->isNewRecord;
        $translations = [];

        if (!$model->validate()) {
            return false;
        }

        foreach ($properties as $property) {
            if (!empty($model->$property)) {
                $translations[$property] = $model->$property;
            }
        }
        if ($isNewRecord) {
            foreach ($properties as $property) {
                if (isset($translations[$property])) {
                    $model->$property = uniqid();
                }
            }
            $isSaved = $isSaved && $model->save();
        }

        foreach ($properties as $property) {
            if (!isset($translations[$property])) {
                continue;
            }
            $translation = $translations[$property];

            if (!$isNewRecord) {
                $label = Label::find()->where(['code' => $oldAttributes[$property]])->one();

//                if (isset($clientId)) {
//                    $label->client_id = $clientId;
//                }
                if (isset($label)) {
                    $label->client_id = null;
                    $label->code = $model->getLabelCodeForProperty($property);
                    $isSaved = $isSaved && $label->save();
                    $labelLanguage = LabelLanguage::find()->where(['label_id' => $label->id, 'language_id' => $languageId])->one();
                }
            }

            if (!isset($label)) {
                $label = new Label();
//                if (isset($clientId)) {
//                    $label->client_id = $clientId;
//                }
                $label->code = $model->getLabelCodeForProperty($property);
                $label->description = 'Label for ' . $label->code;
                $label->record_type = RecordType::Active;
                $isSaved = $isSaved && $label->save();
            }

            $model->$property = $label->code;


            if (!isset($labelLanguage)) {
                $labelLanguage = new LabelLanguage();
                $labelLanguage->label_id = $label->id;
                $labelLanguage->language_id = $languageId;
                $labelLanguage->record_type = RecordType::Active;
            }

            $labelLanguage->value = $translation;

            if ($isSaved) {
                $isSaved = $isSaved && $labelLanguage->save();
                Yii::$app->globalCache->deleteLabel($label->code);
            }
            $label = null;
            $labelLanguage = null;
        }

        $isSaved = $isSaved && $model->save();

        return $isSaved;
    }

}
