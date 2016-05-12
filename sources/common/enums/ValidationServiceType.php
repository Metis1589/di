<?php
namespace common\enums;

use common\components\language\T;
use Yii;

class ValidationServiceType extends BaseEnum {
    const EagleEye = 'EagleEye';

    public static function getLabels() {
        return [
            self::EagleEye => T::l('Eagle Eye'),
        ];
    }
}

