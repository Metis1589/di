<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-admin',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'admin\controllers',
    'bootstrap' => ['log'],
    'language'=>'en',
    'modules' => ['gii' =>[
            'class' => 'system.gii.GiiModule',
            //'password' => 'password',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1', '*'),
        ],
         'gridview' => '\kartik\grid\Module'
    ],
    'controllerMap' => [
        'manager' => [
            'class' => 'mihaildev\elfinder\Controller',
            'roots' => [
                [
                    'path' => 'pages/',
                    'baseUrl' => 'http://resources.dinein.loc',
                    'basePath' => '/home/ubuntu/images',
                    'name' => 'Pages',
                ],
            ]
        ]
    ],
    'components' => [
        'twillio' => [
            'class' => 'gateway\components\Twillio',
            'sid'   => 'ACed3dbcdca8042573413b49e90b4ac611', // test account sid
            'token' => 'cda91b0a98764b6ac6be24d83a3e5a80'    // test account token
        ],
        'user' => [
            'class' => '\yii\web\User',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'session' => [
            'class' => 'yii\web\CacheSession',
            'timeout' => 60 * 60 * 24
        ],
        'request' => [
            'class' => 'admin\common\components\AdminRequest',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
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
        'view' => [
            'class' => '\admin\common\components\AdminWebView',
            'theme' => [
                'pathMap' => ['@app/views' => '@app/themes/inspina'],
                'baseUrl' => '@web/themes/basic',
            ],

        ],
    ],
    'params' => $params,
];
