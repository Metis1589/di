<?php
namespace common\enums;

use Yii;

class ProjectLimitType {
    const Fixed = 'Fixed';
    const Percent = 'Percent';
    
    public static function getLabels() {
        return [
            self::Fixed => Yii::t('label', 'Fixed'),
            self::Percent => Yii::t('label', 'Percent'),
        ];
    }
}

