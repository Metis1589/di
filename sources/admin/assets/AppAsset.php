<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace admin\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
//    public $jsOptions = [
//        'position' => \yii\web\View::POS_HEAD
//    ];
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'custom/css/custom.css'
    ];
    public $js = [
         'custom/js/kartikGridView.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'admin\assets\FontAwesomeAsset',
        'yii\jui\JuiAsset',
        'admin\assets\PluginsAsset',
    ];
}
