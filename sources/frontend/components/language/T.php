<?php

namespace frontend\components\language;


use Yii;

class T {

    public static function e($message) {
        return Yii::t('error', $message);
    }

    public static function l($message) {
        return Yii::t('label', $message);
    }

    public static function cut($text, $length, $append = '...') {
        $result = substr($text,0,$length);
        $result = (strlen($text)>= $length) ? $result . ' ...' : $result;
        return  $result;
    }
} 