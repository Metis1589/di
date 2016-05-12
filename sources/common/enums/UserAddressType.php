<?php
namespace common\enums;

use common\components\language\T;
use Yii;

class UserAddressType extends BaseEnum {
    const Primary = 'Primary';
    const Delivery = 'Delivery';
    const Billing = 'Billing';

    public static function getLabels() {
        return [
            self::Primary => T::l('Primary'),
            self::Delivery => T::l('Delivery'),
            self::Billing => T::l('Billing'),
        ];
    }
}

