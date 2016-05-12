<?php
namespace common\enums;

use Yii;

class RestaurantPaymentFrequencyType {
    const BiMonthly = 'BiMonthly';
    const Monthly = 'Monthly';
    const Weekly = 'Weekly';
    const Daily = 'Daily';
    
    public static function getLabels() {
        return [
            self::BiMonthly => Yii::t('label', 'BiMonthly'),
            self::Monthly => Yii::t('label', 'Monthly'),
            self::Weekly => Yii::t('label', 'Weekly'),
            self::Daily => Yii::t('label', 'Daily'),
        ];
    }
}

