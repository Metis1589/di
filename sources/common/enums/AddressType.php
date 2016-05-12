<?php
namespace common\enums;

use common\components\language\T;

class AddressType extends BaseEnum {
    const Primary  = 'Primary';
    const Billing  = 'Billing';
    const Delivery = 'Delivery';

    public static function getLabels() {
        return [
            self::Primary  => T::l('Primary'),
            self::Billing  => T::l('Billing'),
            self::Delivery => T::l('Delivery')
        ];
    }
}

