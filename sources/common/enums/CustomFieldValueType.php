<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 6/17/2015
 * Time: 12:45 AM
 */

namespace common\enums;


use Yii;

class CustomFieldValueType extends BaseEnum {
    const String = 'String';
    const Bool = 'Bool';
    const Number = 'Number';

    public static function getLabels() {
        return [
            self::String => Yii::t('label', 'String'),
            self::Bool => Yii::t('label', 'Boolean'),
        ];
    }
}