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

class LaterDateValidator extends Validator {

    protected function validateValue($value)
    {
        $date_regex = '/^(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])$/';

        if (!preg_match($date_regex, $value)) {
            return [$this->message, []];
        }

        try{
            $today = new \DateTime();
            $yesterday = (new \DateTime($today->format('Y-m-d')))->sub(new \DateInterval('P1D'));
            $valueDate = new \DateTime($value);
            if($yesterday >= $valueDate){
                return [$this->message, []];
            }
        }catch(\Exception $e){
            return [$this->message, []];
        }

        return null;
    }
}