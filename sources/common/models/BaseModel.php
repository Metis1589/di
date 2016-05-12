<?php

namespace common\models;

use common\components\GlobalCacheMessageSource;
use ReflectionClass;
use Yii;
use yii\base\Exception;

class BaseModel extends \yii\db\ActiveRecord
{
    public function beforeSave($insert) {
//        foreach ($this->attributes as $attribute => $value) {
//            if (!empty($value)) {
//                $this->$attribute = \yii\helpers\Html::encode($value);
//            }
//        }
        return parent::beforeSave($insert);
    }


    public function afterFind() {
        parent::afterFind();
        $this->convertBooleanAttributes();

        $translatedProperties = $this->translatedProperties();
        foreach($translatedProperties as $property) {
            $this->$property = Yii::$app->globalCache->getLabel($this->$property);
        }
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        $this->convertBooleanAttributes();
    }
    
    public static function find()
    {
        $isCacheInitilized = Yii::$app->globalCache->isInitializing();
        $cacheAttributes = static::cacheAttributes();
        if ($isCacheInitilized && !empty($cacheAttributes)) {
            return parent::find()->select($cacheAttributes);
        }
        return parent::find();
    }
    
    protected static function cacheAttributes() {
        return [];
    }

    public function load($data, $formName = null) {

        if (array_key_exists('record_type', $data) && empty($data['record_type'])) {
            unset($data['record_type']);
        }
        if (array_key_exists('create_on', $data)) {
            unset($data['create_on']);
        }
        if (array_key_exists('last_update', $data)) {
            unset($data['last_update']);
        }
       return parent::load($data, $formName);
    }

    public function translatedProperties() {
        return [];
    }

    public function getLabelCodeForProperty($property) {
        $reflect = new ReflectionClass($this);
        if (!isset($this->id)) {
            throw new Exception('Can not create label code for model without id');
        }
        return GlobalCacheMessageSource::getLabelName($reflect->getShortName(). ' ' .$property. ' ' . $this->id);
    }

    private function convertBooleanAttributes() {
        $rules = $this->rules();
        foreach($rules as $rule) {
            if ($rule[1] === 'boolean') {
                $properties = $rule[0];
                if (is_array($properties)) {
                    foreach($properties as $property) {
                        $this->$property = $this->$property ? true : false;
                    }
                } else {
                    $this->$properties = $this->$properties ? true : false;
                }

            }
        }
    }


}