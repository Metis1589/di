<?php
return [
    'timeZone' => 'Europe/London',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=b32b0face5fec715a8292d5bfb8535304178129b.rackspaceclouddb.com;dbname=new',
            'username' => 'apextech',
            'password' => '{{DB_PASSWORD}}',
            'charset' => 'utf8',
        ],
        'cache' => [
            'class' => 'yii\caching\MemCache',
            'servers' => [
                [
                    'host' => '10.179.197.6',
                    'port' => 11211,
                    'weight' => 60,
                ],
            ],
        ],
        'globalCache' => [
            'class' => '\common\components\cache\GlobalCache',
            'innerKey' => 'dev_key'
        ],
        'mailer' => [
            'class' => 'common\components\mail\Mailer',
            'viewPath' => '@common/mail',
            'from' => 'support@apextechinc.com',
            'redirectTo' => 'seleznyovdenis71@gmail.com',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mandrillapp.com',
                'username' => 'evan@dinein.co.uk',
                'password' => '{{MANDRILL_PASSWORD}}',
                'port' => '587',
                // 'encryption' => 'ssl',
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
