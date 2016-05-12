<?php
namespace common\enums;

use common\components\language\T;
use Yii;

class RestaurantAddressType extends BaseEnum {
    const Physical = 'Physical';
    const Pickup = 'Pickup';

    public static function getLabels() {
        return [
            self::Physical => T::l('Restaurant Physical'),
            self::Pickup => T::l('Restaurant Pickup'),
        ];
    }
}

