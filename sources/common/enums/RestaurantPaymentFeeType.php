<?php
namespace common\enums;

use Yii;

class RestaurantPaymentFeeType {
    const VatExclusive = 'VatExclusive';
    const VatInclusive = 'VatInclusive';

    public static function getLabels() {
        return [
            self::VatExclusive => Yii::t('label', 'VatExclusive'),
            self::VatInclusive => Yii::t('label', 'VatInclusive'),
        ];
    }
}

