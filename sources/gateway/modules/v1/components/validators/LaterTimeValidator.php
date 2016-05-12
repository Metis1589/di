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

class LaterTimeValidator extends Validator {

    protected function validateValue($value)
    {
        $exp = "/(2[0-3]|[01][0-9]):[0-5][0-9]/";

        $ar = explode('-', $value);

        if (count($ar) != 2) {
            return [$this->message, []];
        }

        if (!preg_match($exp, $ar[0]) || !preg_match($exp, $ar[1])) {
            return [$this->message, []];
        }

        return null;
    }
}