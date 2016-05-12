<?php
namespace common\enums;

use common\components\language\T;
use Yii;

class VoucherGenerateBy extends BaseEnum {
    const M = 'M';
    const A = 'A';

    public static function getLabels() {
        return [
            self::M => T::l('M'),
            self::A => T::l('A'),
        ];
    }
}

