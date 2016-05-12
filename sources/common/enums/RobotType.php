<?php
namespace common\enums;

use Yii;

class RobotType {

    const Member = 'Member';
    const CorporateMember = 'CorporateMember';
    const Admin = 'Admin';
    
    const UNAUTHORIZED_USER_ID = 'UNAUTHORIZED_USER_ID';
    

    public static function getLabels() {
        return [
            self::Member => Yii::t('label', 'Member'),
            self::CorporateMember => Yii::t('label', 'CorporateMember'),
            self::Admin => Yii::t('label', 'Admin'),
            self::RestaurantAdmin => Yii::t('label', 'RestaurantAdmin'),
            self::RestaurantGroupAdmin => Yii::t('label', 'RestaurantGroupAdmin'),
            self::RestaurantChainAdmin => Yii::t('label', 'RestaurantChainAdmin'),
            self::CorporateAdmin => Yii::t('label', 'CorporateAdmin'),
            self::RestaurantTeam => Yii::t('label', 'RestaurantTeam'),
            self::Finance => Yii::t('label', 'Finance'),
            self::ClientAdmin => Yii::t('label', 'ClientAdmin'),
        ];
    }

    public static function getLabelsByModel($model) {
        $labels = self::getLabels();
        $types = array_keys(self::getUserTypes($model));
        $result = [];
        foreach($labels as $key => $value) {
            if (in_array($key, $types)) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public static function getUserTypes($model) {
        if ($model == 'restaurant') {
            return [
                self::RestaurantAdmin => Yii::t('label', 'RestaurantAdmin'), 
                self::RestaurantTeam => Yii::t('label', 'RestaurantTeam')];
        } elseif ($model == 'restaurant_admin') {
            return [
                self::Member => Yii::t('label', 'Member'),
                self::CorporateMember => Yii::t('label', 'CorporateMember'),
                self::RestaurantGroupAdmin => Yii::t('label', 'RestaurantGroupAdmin'),
                self::RestaurantChainAdmin => Yii::t('label', 'RestaurantChainAdmin'),
                self::CorporateAdmin => Yii::t('label', 'CorporateAdmin'),
                self::ClientAdmin => Yii::t('label', 'ClientAdmin'),
                self::RestaurantAdmin => Yii::t('label', 'RestaurantAdmin'),
                self::RestaurantTeam => Yii::t('label', 'RestaurantTeam')
            ];
        }
    }


}
