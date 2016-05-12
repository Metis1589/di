<?php
namespace common\enums;

use Yii;

class PersonTitle {
    const Ms = 'Ms';
    const Mrs = 'Mrs';
    const Mr = 'Mr';
    
    public static function getLabels() {
        return [
            self::Ms => Yii::t('label', 'Ms'),
            self::Mrs => Yii::t('label', 'Mrs'),
            self::Mr => Yii::t('label', 'Mr'),
        ];
    }
}

