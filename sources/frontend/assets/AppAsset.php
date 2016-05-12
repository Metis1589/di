<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/jquery.dineinSelect.css',
        'css/ngProgress.css',
        'css/magnific-popup.css',
        'css/style.css',
        'css/index.css',
        'css/restaurants.css',
        'css/restaurant_index.css',
        'css/checkout_info.css',
        'css/pushy_menu.css',
        'css/checkout_info.css',
        'css/user_section_style.css',
        'css/delivery_tracker.css',
        'css/custom.css',
        'css/jquery.mobile.custom.structure.min',
        'css/jquery.mobile.custom.theme.min'
    ];

    public $js = [
        'js/jquery-1.11.1.js',
        'js/jquery.mousewheel.min.js',
        'js/jquery.dineinSelect.js',
        'js/jquery.magnific-popup.min.js',
        'js/restaurant_check.js',
        'js/index.js',
        'js/main_page.js',
        'js/jquery.easypiechart.min.js',
        'js/jquery.mobile.custom.min'
    ];

    public $depends = [
        'frontend\assets\AngularAsset',
        'frontend\assets\AngularControllersAsset',
        'frontend\assets\AngularServicesAsset',
        'frontend\assets\AngularDirectivesAsset',
        'frontend\assets\AngularFiltersAsset'
    ];
}
