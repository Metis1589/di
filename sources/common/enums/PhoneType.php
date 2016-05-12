<?php
namespace common\enums;

use Yii;

class PhoneType {
    const Mobile = 'Mobile';
    const Phone = 'Phone';
    
    public static function getLabels() {
        return [
            self::Mobile => Yii::t('label', 'Mobile'),
            self::Phone => Yii::t('label', 'Phone'),
        ];
    }
}

