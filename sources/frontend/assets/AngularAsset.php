<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class AngularAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/custom/js/';

    public $js = [
        '//ajax.googleapis.com/ajax/libs/angularjs/1.3.12/angular.min.js',
        '//ajax.googleapis.com/ajax/libs/angularjs/1.3.12/angular-route.js',

        'dineinApp.js',

        'components/cookies.js',
        'components/ngProgress.js',
        'components/angular-filter.min.js',
    ];
}
