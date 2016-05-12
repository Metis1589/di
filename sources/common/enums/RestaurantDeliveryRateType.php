<?php
namespace common\enums;

use common\components\language\T;
use Yii;

class RestaurantDeliveryRateType {
    const Free = 'Free';
    const Fixed = 'Fixed';
    const Float = 'Float';
    
    public static function getLabels() {
        return [
            self::Free => T::l('Free'),
            self::Fixed => T::l('Fixed'),
            self::Float => T::l( 'Float'),
        ];
    }
}


