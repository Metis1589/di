<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'common\components\GlobalCacheMessageSource',
                    'cache' => 'globalCache',
                    'forceTranslation' => 1
                ],
            ],
        ],
        'translationLanguage' => [
            'class' => 'common\components\language\TranslationLanguage',
            'language' => 'en',
        ],
        'user' => [
            'class' => 'common\components\identity\WebUser',
            'identityClass' => 'common\models\User'
         ],
        'authManager' => [
            'class' => 'common\components\identity\DineInPhpManager',
            'itemFile' => '@app/../common/config/auth/roles.php',
            'assignmentFile' => '@app/../common/config/auth/assignments.php',
//            'ruleFile' => '@app/../common/config/auth/rules.php',
        ],
        'mailchimp' => [
            'class' => '\common\components\mailchimp\MailChimp',
        ],
        'locationService'=>array(
            'class'=>'\common\components\LocationService',
        ),
    ],
];
