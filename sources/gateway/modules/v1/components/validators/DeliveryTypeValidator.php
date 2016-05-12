<?php

namespace gateway\modules\v1\components\validators;

use common\enums\DeliveryType;
use Yii;
use yii\validators\Validator;

/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 6/9/2015
 * Time: 11:20 PM
 */

class DeliveryTypeValidator extends Validator {

    protected function validateValue($value)
    {
        if ($value != DeliveryType::DeliveryLater && $value != DeliveryType::DeliveryAsap && $value != DeliveryType::CollectionAsap && $value != DeliveryType::CollectionLater) {
            return [$this->message, []];
        }

        return null;
    }
}