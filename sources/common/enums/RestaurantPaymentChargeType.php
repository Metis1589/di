<?php
namespace common\enums;

use Yii;

class RestaurantPaymentChargeType {
    const WebPrice = 'WebPrice';
    const RestaurantPrice = 'RestaurantPrice';
    
    public static function getLabels() {
        return [
            self::WebPrice => Yii::t('label', 'WebPrice'),
            self::RestaurantPrice => Yii::t('label', 'RestaurantPrice'),
        ];
    }
}


