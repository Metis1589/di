<?php

namespace gateway\modules\v1\components\validators;

use Yii;
use yii\validators\Validator;

/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 6/9/2015
 * Time: 11:20 PM
 */

class RestaurantIdValidator extends Validator {

    public $client_key;

    protected function validateValue($value)
    {
        $restaurant = Yii::$app->globalCache->getRestaurant($this->client_key, $value);
        if ($restaurant == null) {
            return [$this->message, []];
        }

        return null;
    }
}