<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-gateway',
    'controllerNamespace' => 'gateway\controllers',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'gateway\modules\v1\Module'
        ],
    ],
    'components' => [
        'twilio' => [
            'class' => 'gateway\components\Twillio',
            'sid'   => 'AC5a7a0c6fa6eb22036376467f6c19d308',
            'token' => 'e3c1f43c84e405e829ca615308b035a4'
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'session' => [
            'class' => 'yii\web\CacheSession',
            'timeout' => 60 * 60 * 24
        ],
        'userCache' => [
            'class' => '\common\components\cache\UserCache',
        ],
        'globalCache' => [
            'class' => '\common\components\cache\GlobalCache',
        ],
        'errorHandler'=>array(
            'class'=>'gateway\components\GatewayErrorHandler',
        ),
        'restaurantService'=>array(
            'class'=>'\gateway\components\RestaurantService',
        ),
        'corporateOrderService'=>array(
            'class'=>'\gateway\components\CorporateOrderService',
        ),
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                ],
            ],
        ],
        'request' => [
            'class' => 'gateway\components\GatewayRequest',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ''     => 'site/index',
                '<controller:[\w\-]+>/<action:[\w\-]+>'     => '<controller>/<action>',
                'v1/<controller:[\w\-]+>/<action:[\w\-]+>'  => 'v1/<controller>/<action>',
            ],
        ],

    ],
    'params' => $params,
];



