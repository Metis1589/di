<?php
namespace common\enums;

use Yii;

class PaymentFrequencyType {
    const Daily     = 'Daily';
    const Weekly    = 'Weekly';
    const Monthly   = 'Monthly';
    const BiMonthly = 'Bi-Monthly';
    
    public static function getLabels() {
        return [
            self::Daily     => Yii::t('label', 'Daily'),
            self::Weekly    => Yii::t('label', 'Weekly'),
            self::Monthly   => Yii::t('label', 'Monthly'),
            self::BiMonthly => Yii::t('label', 'Bi-Monthly'),
        ];
    }
}
