<?php
namespace common\enums;

use Yii;

class CompanyLimitType {
    const Soft = 'Soft';
    const Hard = 'Hard';
    
    public static function getLabels() {
        return [
            self::Soft => Yii::t('label', 'Soft'),
            self::Hard => Yii::t('label', 'Hard'),
        ];
    }
}

