<?php
namespace common\enums;

use common\components\language\T;

class NotificationMessageType extends BaseEnum {
    const OrderCancelled = 'OrderCancelled';

    public static function getLabels() {
        return [
            self::OrderCancelled => T::l('Order $id Cancelled')
        ];
    }
}

