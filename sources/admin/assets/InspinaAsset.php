<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace admin\assets;

use yii\web\AssetBundle;

class InspinaAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/inspina';

    public $css = [
        'css/animate.css',
        'css/style.css',
        'css/chosen/chosen.css',
        'css/iCheck/custom.css'
    ];

    public $js = [
        'js/plugins/metisMenu/jquery.metisMenu.js',
        'js/plugins/slimscroll/jquery.slimscroll.min.js',
        'js/inspinia.js',
        'js/plugins/pace/pace.min.js',
        'js/plugins/iCheck/icheck.min.js',
        'js/plugins/chosen/chosen.jquery.js',
    ];
}
