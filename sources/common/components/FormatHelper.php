<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 12/10/2014
 * Time: 3:01 PM
 */

namespace common\components;


use common\models\Address;
use Yii;

class FormatHelper {

    public static function formatFloatValue($value,$digits) {
        return number_format(floor($value * 100) / 100, $digits);
    }
    
    public static function formatDateValue($value) {
        return Yii::$app->formatter->asDatetime($value);
    }
    
    public static function formatTime($value) {
        return Yii::$app->formatter->asTime($value);
    }

    public static function formatAddress($addressData){
        $return = '<b>'.$addressData['first_name'].' '.$addressData['last_name'].'</b><br>';
        $return .= $addressData['address1'].'<br>';
        if($addressData['address2']){
            $return .= $addressData['address2'].'<br>';
        }
        if($addressData['address3']){
            $return .= $addressData['address3'].'<br>';
        }
        $return .= $addressData['postcode'].', '.$addressData['city'];
        return $return;
    }

    public static function formatRestaurantAddress($restarauntModel){
        $return = '<b>'.$restarauntModel['name'].'</b><br>';
        if($restarauntModel->pickupAddress){
            $addressModel = $restarauntModel->pickupAddress;
            $return .= $addressModel['address1'].'<br>';
            if($addressModel['address2']){
                $return .= $addressModel['address2'].'<br>';
            }
            if($addressModel['address3']){
                $return .= $addressModel['address3'].'<br>';
            }
            $return .= $addressModel['postcode'].', '.$addressModel['city'];
        }
        return $return;
    }
} 