<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class AngularControllersAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/custom/js/controllers/';

    public $js = [
        'userProfileController.js',
        'orderTrackerController.js',
        'menuController.js',
        'cartController.js',
        'loginController.js',
        'registrationController.js',
        'deliveryInfoController.js',
        'checkoutController.js'
    ];
}
