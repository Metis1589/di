<?php
/**
 * Created by PhpStorm.
 * User: Serginnios
 * Date: 4/7/2015
 * Time: 7:28 PM
 */

namespace admin\common\components;


use yii\web\Application;

class AdminApplication extends Application {

    public function init() {
        parent::init();
        $this->globalCache->initialize();
        $this->homeUrl = '/order/index';
    }
} 