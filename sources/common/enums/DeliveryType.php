<?php
namespace common\enums;

use Yii;

class DeliveryType
{
    const DeliveryAsap = 'DeliveryAsap';
    const DeliveryLater = 'DeliveryLater';
    const CollectionAsap = 'CollectionAsap';
    const CollectionLater = 'CollectionLater';

    public static function getLabels() {
        return [
            self::DeliveryAsap => Yii::t('label', 'Delivery Asap'),
            self::DeliveryLater => Yii::t('label', 'Delivery Later'),
            self::CollectionAsap => Yii::t('label', 'Collect Asap'),
            self::CollectionLater => Yii::t('label', 'Collect Later'),
        ];
    }
}

