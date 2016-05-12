<?php
namespace common\enums;

use Yii;
use \common\components\language\T;

class UserType extends BaseEnum {

    const Member = 'Member';
    const CorporateMember = 'CorporateMember';
    const Admin = 'Admin';
    const RestaurantAdmin      = 'RestaurantAdmin';
    const RestaurantGroupAdmin = 'RestaurantGroupAdmin';
    const RestaurantChainAdmin = 'RestaurantChainAdmin';
    const CorporateAdmin = 'CorporateAdmin';
    const RestaurantTeam = 'RestaurantTeam';
    const Finance = 'Finance';
    const ClientAdmin = 'ClientAdmin';
    const RestaurantApp = 'RestaurantApp';
    const DispatchApp = 'DispatchApp';
    const InnTouch = 'InnTouch';
    const UNAUTHORIZED = 'Unauthorized';
//    const AUTHORIZED = 'Authorized';

    const UNAUTHORIZED_USER_ID = 'UNAUTHORIZED_USER_ID';


    public static function getLabels() {
        return [
            self::Member               => T::l('Member'),
            self::CorporateMember      => T::l('Corporate Member'),
            self::Admin                => T::l('Admin'),
            self::RestaurantAdmin      => T::l('Restaurant Admin'),
            self::RestaurantGroupAdmin => T::l('Restaurant Group Admin'),
            self::RestaurantChainAdmin => T::l('Restaurant Chain Admin'),
            self::CorporateAdmin       => T::l('Corporate Admin'),
            self::RestaurantTeam       => T::l('Restaurant Team'),
            self::Finance              => T::l('Finance'),
            self::ClientAdmin          => T::l('Client Admin'),
            self::RestaurantApp        => T::l('Restaurant Application'),
            self::InnTouch              => T::l('InnTouch'),
        ];
    }

    public static function getLabelsByModel($model) {
        $labels = self::getLabels();
        $types  = array_keys(self::getUserTypes($model));
        $result = [];
        foreach($labels as $key => $value) {
            if (in_array($key, $types)) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public static function getUserTypes($model) {
        switch ($model) {
            case 'restaurant':
                return [
                    self::RestaurantAdmin => T::l('RestaurantAdmin'),
                    self::RestaurantTeam  => T::l('RestaurantTeam')
                ];
                break;
            case 'restaurant_admin':
                return [
                    self::Member               => T::l('Member'),
                    self::CorporateMember      => T::l('CorporateMember'),
                    self::RestaurantGroupAdmin => T::l('RestaurantGroupAdmin'),
                    self::RestaurantChainAdmin => T::l('RestaurantChainAdmin'),
                    self::CorporateAdmin       => T::l('CorporateAdmin'),
                    self::ClientAdmin          => T::l('ClientAdmin'),
                    self::RestaurantAdmin      => T::l('RestaurantAdmin'),
                    self::RestaurantTeam       => T::l('RestaurantTeam'),
                    self::RestaurantApp        => T::l('Restaurant Application'),
                    self::InnTouch             => T::l('InnTouch')
                ];
                break;
            case 'company_member':
                return [ self::CorporateMember => T::l('CorporateMember') ];
                break;
            case 'company_admin':
                return [ self::CorporateAdmin => T::l('CorporateAdmin') ];
                break;
        }
    }
}