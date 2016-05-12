<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 12/3/2014
 * Time: 12:47 AM
 */

namespace admin\common;

use Yii;

class FilterHelper {

    public static function yesNoValues() {
        return [Yii::t('label','No'), Yii::t('label','Yes')];
    }

    public static function recordTypeValues() {
        return ['Active' => Yii::t('label','Active'), 'Inactive' => Yii::t('label','Inactive')];
    }

}