<?php

return [
    'language' => 'th',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
];
