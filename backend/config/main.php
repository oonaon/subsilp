<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php', require __DIR__ . '/../../common/config/params-local.php', require __DIR__ . '/params.php', require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log',
        [
            'class' => 'common\components\LanguageSelector',
            'supportedLanguages' => ['en', 'th'],
        ],
        [
            'class' => 'common\components\OrganizeSelector',
            'supportedOrganize' => ['con', 'easy'],
        ]
    ],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
            'theme' => [
                // 'basePath' => '@backend/themes/adminlte/views',
                // 'baseUrl' => '@backend/themes/adminlte/views',
                'pathMap' => [
                    '@app/views' => '@backend/themes/adminlte/views',
                    '@app/widgets' => '@backend/themes/adminlte/views/widgets',
                ]
            ]
        ]
    ],
    'container' => [
        'definitions' => [
      //      yii\widgets\ActiveForm::class => [
      //          'fieldClass' => 'common\components\CActiveField',
      //      ],
            yii\grid\GridView::class => [
                'layout' => "{items}\n{summary} {pager}",
                'summary' => "",
             //   'tableOptions' => ['class' => 'table table-hover'],
            ],
            yii\grid\ActionColumn::class => [
                'buttonOptions' => ['class' => 'btn btn-default btn-xs'],
                //'template' => '{view} {delete}',
                'template' => '{view} {delete}',
                'buttons' => [
                    'delete' => function($url, $model) {
                        if (empty($url)) {
                            return yii\helpers\Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                        'class' => 'btn btn-default btn-xs',
                                        'disabled' => true,
                            ]);
                        } else {
                            return yii\helpers\Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                        'class' => 'btn btn-default btn-xs',
                                        'data' => [
                                            'confirm' => Yii::t('backend/general', 'confirm_delete'),
                                            'method' => 'post',
                                        ],
                            ]);
                        }
                    },
                    'update' => function($url, $model) {
                        return yii\helpers\Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                    'class' => 'btn btn-default btn-xs',
                                    'data' => [
                                        'pjax' => 0,
                                    ],
                        ]);
                    },
                    'view' => function($url, $model) {
                        return yii\helpers\Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'class' => 'btn btn-default btn-xs',
                                    'data' => [
                                        'pjax' => 0,
                                    ],
                        ]);
                    },
                    'modal' => function($url, $model) {
                        if (empty($url)) {
                            return yii\helpers\Html::a('<span class="glyphicon glyphicon-edit"></span>', $url, [
                                        'class' => 'btn btn-default btn-xs',
                                        'disabled' => true,
                            ]);
                        } else {
                            return yii\helpers\Html::a('<span class="glyphicon glyphicon-edit"></span>', $url, [
                                        'class' => 'btn btn-default btn-xs',
                                        'data' => [
                                            'method' => 'post',
                                            'toggle' => 'modal',
                                            'target' => '#modal-ajax',
                                        ],
                            ]);
                        }
                    },
                    'default' => function($url, $model) {
                        if ($model->item_default) {
                            $icon = '<span class="fa fa-fw fa-circle"></span>';
                        } else {
                            $icon = '<span class="fa fa-fw fa-circle-o"></span>';
                        }
                        return yii\helpers\Html::a($icon, $url, [
                                    'class' => 'btn btn-default btn-xs',
                                    'data' => [
                                        'confirm' => Yii::t('backend/general', 'confirm_defalt'),
                                        'method' => 'post',
                                    ],
                        ]);
                    },
                ],
                /*
                  'urlCreator' => function ($button, $item, $key, $index) {
                  if ($button === 'view') {
                  $url = ['view', 'id' => $item->id];
                  return $url;
                  }
                  if ($button === 'update') {
                  $url = ['update', 'id' => $item->id];
                  return $url;
                  }
                  if ($button === 'delete') {
                  $url = ['delete', 'id' => $item->id];
                  return $url;
                  }
                  },

                 */
                'contentOptions' => [
                    'noWrap' => true,
                    'style' => 'width: 100px;'
                ],
            ],
            yii\data\Pagination::class => [
                'defaultPageSize' => 15,
            ],
            yii\widgets\LinkPager::class => [
                'options' => ['class' => 'pagination pagination-sm no-margin pull-right'],
                'prevPageLabel' => '<i class="fa fa-angle-left"></i>',
                'nextPageLabel' => '<i class="fa fa-angle-right"></i>',
                'firstPageLabel' => '<i class="fa fa-angle-double-left"></i>',
                'lastPageLabel' => '<i class="fa fa-angle-double-right"></i>',
            ],
        ],
    ],
    'params' => $params,
];

