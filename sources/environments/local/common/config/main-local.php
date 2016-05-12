<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=server:3306;dbname=di_pe',
            'username' => 'alex.popov',
            'password' => 'P@ssword1',
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
            'innerKey' => 'local_key'
        ],
        'mailer' => [
            'class' => 'common\components\mail\Mailer',
            'viewPath' => '@common/mail',
            'from' => 'support@apextechinc.com',
            'redirectTo' => '',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
               'class' => 'Swift_SmtpTransport',
               'host' => 'smtp.gmail.com',
               'username' => 'support@apextechinc.com',
               'password' => '',
               'port' => '465',
               'encryption' => 'ssl',
           ], 
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'php:Y-m-d',
            'datetimeFormat' => 'php:jS M Y H:i:s',
            'timeFormat' => 'php:H:i:s', 
        ],
    ],
];
