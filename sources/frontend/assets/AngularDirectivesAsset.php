<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class AngularDirectivesAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/custom/js/directives/';

    public $js = [
        'compareTo.js',
        'validationSummary.js',
        'deliveryType.js',
        'dineinSelect.js',
        'stickyString.js',
        'repeatEnd.js',
        'menuOption.js',
        'resizeNotifier.js',
        'pushMenu.js',
        'numericOnly.js',
        'numericOnlyPositive.js',
        'money.js',
        'titleSelect.js',
        'onEnter.js',
        'removeHtml.js',
        'dineinTooltip.js',
        'onFocusScrollTo.js',
    ];
}
