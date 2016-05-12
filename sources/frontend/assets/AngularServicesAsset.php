<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class AngularServicesAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/custom/js/services/';

    public $js = [
        'apiService.js',
        'orderService.js',
        'restaurantService.js',
        'userService.js',
        'recursionHelper.js'
    ];
}
