<?php

namespace gateway\components;

use yii\web\Request;

class GatewayRequest extends Request {

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