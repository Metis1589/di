<?php
namespace common\enums;

use Yii;

class RestaurantScheduleType extends  BaseEnum {
    const OpenTime = 'OpenTime';
    const DeliveryTime = 'DeliveryTime';
    
    public static function getLabels() {
        return [
            self::OpenTime => Yii::t('label', 'Open Time'),
            self::DeliveryTime => Yii::t('label', 'Delivery Time'),
        ];
    }
}

