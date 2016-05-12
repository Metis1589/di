<?php
$params = array_merge(
//    require(__DIR__ . '/../../common/config/params.php'),
//    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => ['gii' =>[
        'class' => 'system.gii.GiiModule',
        //'password' => 'password',
        // If removed, Gii defaults to localhost only. Edit carefully to taste.
        //'ipFilters' => array('127.0.0.1', '::1', '*'),
    ]],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'frontend\components\FrontendMessageSource',
                    'cache' => 'frontendCache',
                    'forceTranslation' => 1
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'translationLanguage' => [
            'class' => 'common\components\language\TranslationLanguage',
            'language' => 'en',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ''                      => '/site/index',
                '/register'             => '/site/register',
                '/login'                => '/site/login',
                '/logout'               => '/site/logout',
                '/password-reset'       => '/site/request-password-reset',
                '/reset-password'       => '/site/reset-password',
                '/activate'             => '/site/activate',
                '/site-map'             => '/site/site-map',
                '/contact-us'           => '/site/contact',
                '/suggest-a-restaurant' => '/site/suggest-restaurant',
                '/restaurant-sign-up'   => '/site/restaurant-sign-up',
                '/alergy-information'   => '/site/allergies',
                '<url:[\w\-]+>'         => '/page/page',
                '<id:[\d]+>/restaurant/<seoarea:[\w\-]*>/<cuisine:[\w\-]*>/<slug:[\w\-]*>.html' => 'restaurant/view',
                '<cuisine_id:[\d]+>/restaurants/cuisine/<seo_name:[\w\-]*>.html' => 'restaurant/search',
                '<seo_area_id:[\d]+>/london_restaurant_delivery_in/<seo_name:[\w\-]*>.html' => 'restaurant/search',
                '<controller:[\w\-]+>/<action:[\w\-]+>'    => '<controller>/<action>',
                'v1/<controller:[\w\-]+>/<action:[\w\-]+>' => 'v1/<controller>/<action>',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'deliveryDatesService' => [
            'class'            => 'frontend\components\DeliveryDatesService',
            'today_time_delay' => 20,
            'from_time'        => '00:00',
            'to_time'          => '23:59',
            'time_interval'    => 30,
            'number_of_days'   => 5
        ]
    ],
    'params' => $params,
];
