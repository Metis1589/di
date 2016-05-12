<?php
namespace common\enums;

use common\components\language\T;
use Yii;

class RestaurantPaymentType extends BaseEnum {
    const Bank = 'Bank';
    const Cash = 'Cash';

    public static function getLabels() {
        return [
            self::Bank => T::l('Bank'),
            self::Cash => T::l('Cash')
        ];
    }
}


