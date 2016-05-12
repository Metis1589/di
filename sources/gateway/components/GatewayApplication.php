<?php
/**
 * Created by PhpStorm.
 * User: Serginnios
 * Date: 4/7/2015
 * Time: 7:28 PM
 */

namespace gateway\components;


use yii\web\Application;

class GatewayApplication extends Application {

    public function init() {
        parent::init();
        $this->globalCache->initialize();
    }
} 