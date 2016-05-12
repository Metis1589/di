<?php
namespace common\enums;

use common\components\language\T;
use Yii;

class DayTimeType extends BaseEnum{
    const Morning = 'Morning';
    const Evening = 'Evening';
    
    public static function getLabels() {
        return [
            self::Morning => T::l('Morning'),
            self::Evening => Yii::t('label', 'Evening'),
        ];
    }
}

