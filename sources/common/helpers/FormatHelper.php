<?php
namespace common\helpers;

use Yii;

class FormatHelper {
    
    const date_format = 'yyyy-MM-dd';
    
    public static function convertDateToMySql($date) {
        return date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $date)));
    }
    
    public static function convertFromMySql($date) {
        return Yii::$app->formatter->asDate($date, self::date_format);
    }

}
