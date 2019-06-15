<?php

return [
    'language' => 'th',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'rbac' => [
            'class' => 'yii2mod\rbac\Module',
            'layout' => '@backend/views/layouts/main_solid',
            'as access' => [
                'class' => yii2mod\rbac\filters\AccessControl::class
            ],
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest', 'user'],
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'common\components\CDbMessageSource',
                    'enableCaching' => false,
                    'messageTable' => '{{%message}}',
                    'sourceMessageTable' => '{{%message_source}}',
                    'forceTranslation' => true
                ],
            ],
        ],
    ],
    'as access' => [
        'class' => yii2mod\rbac\filters\AccessControl::class,
        'allowActions' => [
            'site/*',
            'admin/*',
        ]
    ],
];
