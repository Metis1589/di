<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class AngularFiltersAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/custom/js/filters/';

    public $js = [
        'cut.js',
    ];
}
