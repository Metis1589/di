<?php
namespace common\enums;

use Yii;

class BaseEnum {

    public static function values() {
        return array_keys(static::getLabels());
    }

    public static function getLabels() {
        return [];
    }
}

