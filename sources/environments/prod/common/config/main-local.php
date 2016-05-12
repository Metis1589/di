<?php
return [
    'timeZone' => 'Europe/London',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=dev.dinein.co.uk:3306;dbname=new_dinein',
            'username' => 'apextech',
            'password' => '',
            'charset' => 'utf8',
        ],
        'cache' => [
            'class' => 'yii\caching\MemCache',
            'servers' => [
                [
                    'host' => '127.0.0.1',
                    'port' => 11211,
                    'weight' => 60,
                ],
            ],
        ],
        'globalCache' => [
            'class' => '\common\components\cache\GlobalCache',
            'innerKey' => 'prod_key'
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'php:Y-m-d',
            'datetimeFormat' => 'php:jS M Y H:i:s',
            'timeFormat' => 'php:H:i:s', 
        ],
    ],
];
