<?php
namespace common\enums;

use common\components\language\T;
use Yii;

class CompanyAddressType extends BaseEnum {
    const Physical = 'Physical';
    const Billing  = 'Billing';
    const Delivery = 'Delivery';

    public static function getLabels() {
        return [
            self::Physical => T::l('Company Physical'),
            self::Billing  => T::l('Company Billing'),
            self::Delivery => T::l('Company Delivery'),
        ];
    }
}

