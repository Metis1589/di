<?php
    
namespace admin\assets;

use yii\web\AssetBundle;

class PluginsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/custom';

    public $css = [
        '/css/plugins/jsTree/style.min.css',
    ];

    public $js = [
        '/js/plugins/jquery.plugin.min.js',
        '/js/plugins/jquery.timeentry.min.js',
        '/js/plugins/jstree.min.js',
        '/js/plugins/jquery-ui-timepicker-addon.js',

        '/js/plugins/timepicker.js',
        '/js/plugins/emails.js',
        '/js/plugins/juiDatetimePicker.js',
        '/js/plugins/juiDatePicker.js',
        
        '/js/plugins/checkboxStyle.js',
        '/js/plugins/restaurantsGroupTree.js',
        '/js/plugins/gridMultipleAction.js'
    ];
}