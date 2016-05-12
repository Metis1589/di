<?php
namespace common\enums;

use Yii;

class Day extends BaseEnum {
    const Monday = 'Monday';
    const Tuesday = 'Tuesday';
    const Wednesday = 'Wednesday';
    const Thursday = 'Thursday';
    const Friday = 'Friday';
    const Saturday = 'Saturday';
    const Sunday = 'Sunday';
    
    public static function getLabels() {
        return [
            self::Monday => Yii::t('label', 'Monday'),
            self::Tuesday => Yii::t('label', 'Tuesday'),
            self::Wednesday => Yii::t('label', 'Wednesday'),
            self::Thursday => Yii::t('label', 'Thursday'),
            self::Friday => Yii::t('label', 'Friday'),
            self::Saturday => Yii::t('label', 'Saturday'),
            self::Sunday => Yii::t('label', 'Sunday'),
        ];
    }

    public static function getDay($type) {
        switch($type) {
            case self::Monday:
                return 1;
            case self::Tuesday:
                return 2;
            case self::Wednesday:
                return 3;
            case self::Thursday:
                return 4;
            case self::Friday:
                return 5;
            case self::Saturday:
                return 6;
            case self::Sunday:
                return 0;
        }
    }
}

