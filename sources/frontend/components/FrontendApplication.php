<?php

namespace frontend\components;


use yii\web\Application;

class FrontendApplication extends Application {

    public function init() {
        parent::init();
        $this->frontendCache->initialize();
    }
} 