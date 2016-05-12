<?php
namespace common\enums;

use common\components\language\T;
use Yii;

class RestaurantContactRole extends BaseEnum {
    const Contact = 'Contact';
    const Billing = 'Billing';
    
    public static function getLabels() {
        return [
            self::Contact => T::l('Contact'),
            self::Billing => T::l('Billing'),
        ];
    }
}

