<?php
namespace common\enums;

use common\components\language\T;
use Yii;

class AddressTitle extends BaseEnum {
    const Mr = 'Mr';
    const Mrs = 'Mrs';
    const Ms = 'Ms';
    const Miss = 'Miss';

    public static function getLabels() {
        return [
            self::Miss => T::l('Miss'),
            self::Ms => T::l('Ms'),
            self::Mrs => T::l('Mrs'),
            self::Mr => T::l('Mr'),
        ];
    }
}

