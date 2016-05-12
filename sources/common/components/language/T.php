<?php

namespace common\components\language;


use Yii;

class T {

    public static function e($message) {
        return Yii::t('error', $message);
    }

    public static function l($message) {
        return Yii::t('label', $message);
    }
} 