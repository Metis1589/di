<?php

namespace frontend\components;

use Yii;

class ApiHelper {

    public static function getClientData() {
        $response = file_get_contents(Yii::$app->params['gateway_url'] . 'get-client-data?client_key=' . Yii::$app->params['client_key']);
        return json_decode($response, true)['data'];
    }

    public static function getClientDataLoadTime() {
        $response = file_get_contents(Yii::$app->params['gateway_url'] . 'get-client-data-load-time?client_key=' . Yii::$app->params['client_key']);
        return json_decode($response, true)['data'];
    }
//
//    private $data;
//
//    public function __construct() {
//        $response = file_get_contents('http://gateway.dinein.loc/v1/common/get-client-data');
//        $this->data = json_decode($response, true)['data'];
//    }
//
//    public function getCuisines($language_code) {
//        $languageIsoCode = substr($language_code, 0, 2);
//        return $this->data['cuisines'][$languageIsoCode];
//    }
//
//    public function getPriceRanges() {
//        return $this->data['price_ranges'];
//    }
//
//    public function getRatings() {
//        return $this->data['ratings'];
//    }
//
//    public function getRestaurant($id) {
//        return $this->data['restaurants'][$id];
//    }

}