<?php
namespace common\enums;

use common\components\language\T;
use Yii;

class VoucherMenuCategoryType extends BaseEnum {
    const Source = 'Source';
    const Target = 'Target';


    public static function getLabels() {
        return [
            self::Source => T::l('Source'),
            self::Target => T::l('Target'),
        ];
    }
}

