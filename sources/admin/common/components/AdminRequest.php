<?php

namespace admin\common\components;

use common\enums\CookieName;
use Yii;
use yii\web\Cookie;
use yii\web\Request;

class AdminRequest extends Request {

    public function getImpersonatedClientId() {
        if (isset(Yii::$app->session['impersonated_client_id'])) {
            return Yii::$app->session['impersonated_client_id'];
        }// explode(',', $this->cookies->getValue(CookieName::AdminImpersonateClient))[0];
    }

    public function getImpersonatedClientName() {
        return Yii::$app->session['impersonated_client_name'];
        //return explode(',', $this->cookies->getValue(CookieName::AdminImpersonateClient))[1];
    }

    public function isImpersonated() {
        return isset(Yii::$app->session['impersonated_client_id']);
        //return $this->cookies->getValue(CookieName::AdminImpersonateClient) != null;
    }
    public function impersonateClient($client) {
        Yii::$app->session['impersonated_client_name'] = $client->name;
        Yii::$app->session['impersonated_client_id'] = $client->id;
//        Yii::$app->response->cookies->add(new Cookie([
//            'name' => CookieName::AdminImpersonateClient,
//            'value' => $client->id.','.$client->name
//        ]));
    }

    public function clearImpersonatedClient() {
//        $cookies = Yii::$app->response->cookies;
//        $cookies->remove(CookieName::AdminImpersonateClient);
//        unset($cookies[CookieName::AdminImpersonateClient]);
        unset(Yii::$app->session['impersonated_client_name']);
        unset(Yii::$app->session['impersonated_client_id']);
    }

    public function getFirstParamValue($keys) {
        foreach($keys as $key) {
            if (isset($_GET[$key])) {
                return $_GET[$key];
            }
            if (isset($_POST[$key])) {
                return $_POST[$key];
            }
        }
        return null;
    }
} 