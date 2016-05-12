<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
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
        'frontendCache' => [
            'class' => '\frontend\components\cache\FrontendCache',
            'innerKey' => 'prod_key'
        ],
    ],
];
