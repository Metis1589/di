<?php

namespace admin\assets;

use yii\web\AssetBundle;

class ThemeAsset extends AssetBundle
{
//    public $jsOptions = [
//        'position' => \yii\web\View::POS_HEAD
//    ];
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'custom/css/custom.css'
    ];
    public $js = [
         'custom/js/kartikGridView.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'admin\assets\FontAwesomeAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'admin\assets\InspinaAsset',
        'admin\assets\PluginsAsset',
    ];
}
