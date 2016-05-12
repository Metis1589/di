<?php
namespace common\enums;

use common\components\language\T;
use Yii;

class VoucherAssignmentType extends BaseEnum {
    const RestaurantChain = 'RestaurantChain';
    const RestaurantGroup = 'RestaurantGroup';
    const Restaurant = 'Restaurant';
    const Client = 'Client';
    const User = 'User';

    public static function getLabels() {
        return [
            self::Client => T::l('Client'),
            self::RestaurantChain => T::l('Restaurant Chain'),
            self::RestaurantGroup => T::l('Restaurant Group'),
            self::Restaurant => T::l('Restaurant'),
            self::User => T::l('User'),
        ];
    }
}

