<?php
namespace common\enums;

use Yii;

class RestaurantGroupUserRole {
    const Admin = 'Admin';
    
    public static function getLabels() {
        return [
            self::Admin => Yii::t('label', 'Admin'),
        ];
    }
}