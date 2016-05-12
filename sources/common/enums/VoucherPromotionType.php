<?php
namespace common\enums;

use common\components\language\T;
use Yii;

class VoucherPromotionType extends BaseEnum {
    const Restaurant = 'Restaurant';
    const Client = 'Client';


    public static function getLabels() {
        return [
            self::Restaurant => T::l('Restaurant'),
            self::Client => T::l('Client'),
        ];
    }
}

