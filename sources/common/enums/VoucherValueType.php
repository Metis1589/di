<?php
namespace common\enums;

use common\components\language\T;
use Yii;

class VoucherValueType extends BaseEnum {
    const Fixed = 'Fixed';
    const Percent = 'Percent';


    public static function getLabels() {
        return [
            self::Fixed => T::l('Fixed'),
            self::Percent => T::l('Percent'),
        ];
    }
}

