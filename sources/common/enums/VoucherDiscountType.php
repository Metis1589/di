<?php
namespace common\enums;

use common\components\language\T;
use Yii;

class VoucherDiscountType extends BaseEnum {
    const Discount = 'Discount';
    const Price = 'Price';


    public static function getLabels() {
        return [
            self::Discount => T::l('Discount'),
            self::Price => T::l('Price'),
        ];
    }
}

