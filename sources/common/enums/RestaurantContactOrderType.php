<?php
namespace common\enums;

use Yii;

class RestaurantContactOrderType extends BaseEnum {
    const Sms = 'Sms';
    const Phone = 'Phone';
    const Email = 'Email';
    const Ivr = 'Ivr';

    public static function getLabels() {
        return [
            self::Phone => Yii::t('label', 'Phone'),
            self::Email => Yii::t('label', 'Email'),
            self::Sms => Yii::t('label', 'Sms'),
            self::Ivr => Yii::t('label', 'Ivr'),
        ];
    }
}


