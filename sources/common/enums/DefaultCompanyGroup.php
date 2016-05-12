<?php
namespace common\enums;

use \common\components\language\T;

class DefaultCompanyGroup {

    const DefaultExternal = 'Default External';
    const DefaultInternal = 'Default Internal';

    public static function getLabels() {
        return [
            self::DefaultExternal => T::l('Default External'),
            self::DefaultInternal => T::l('Default Internal')
        ];
    }
}