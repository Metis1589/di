<?php

namespace admin\assets;

use yii\web\AssetBundle;

class AngularAsset extends AssetBundle
{
    const angular_path = '//ajax.googleapis.com/ajax/libs/angularjs/1.3.12/angular.min.js';

    public $basePath = '@webroot';
    public $baseUrl = '@web/custom/js/apps';

    public $css = [
        '/components/xeditable.css'
    ];

    public $js = [
        self::angular_path,
        '/components/xeditable.min.js',
        'app.js',
    ];

    public $angularApps = [
        'restaurant' => 'restaurant.js',
        'voucher' => 'voucher.js',
        'user' => 'user.js',
        'order' => 'order.js',
        'company' => 'company.js',
        'restaurantGroup' => 'restaurantGroup.js',
        'translation' => 'translation.js',
        'timepicker' => '/directives/timepicker.js',
        'timeBoth' => '/directives/timeBoth.js',
        'equals' => '/directives/equals.js',
        'menuOptions' => 'menuOptions.js',
        'apiService' => '/services/apiService.js',
        'userService' => '/services/userService.js',
        'cookies' => '/components/cookies.js',
        'ngProgress' => '/components/ngProgress.js',
        'timer' => '/components/angular-timer-all.min.js',
    ];

    static $selectedApps;


    public static function register($view, $apps) {
        static::$selectedApps = $apps;
        parent::register($view);
    }

    public function init() {
        parent::init();
        foreach(static::$selectedApps as $app) {
            $existedApp = array_keys($this->angularApps);
            if (in_array($app, $existedApp)) {
                $this->js[] = $this->angularApps[$app];
            }
        }
    }
}
