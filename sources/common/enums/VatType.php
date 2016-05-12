<?php
namespace common\enums;

use Yii;

class VatType {
    const Zero = 'Zero';
    const Standard = 'Standard';
    
    public static function getLabels() {
        return [
            self::Zero => Yii::t('label', 'Zero'),
            self::Standard => Yii::t('label', 'Standard'),
        ];
    }
}

