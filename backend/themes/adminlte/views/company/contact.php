<?php

use yii\grid\GridView;
use yii\helpers\Html;
use common\components\CustomColumn;
use yii\widgets\Pjax;
use common\components\HeadNavigator;

$this->params['header'] = HeadNavigator::header();
$this->params['breadcrumbs'] = HeadNavigator::breadcrumbs($model->getFullName(false));

$this->params['panel'] = [
    'id' => 'tab',
    'tabs' => $tabs,
    'tabs_disabled' => false,
    'disabled' => false,
    'title' => $model->code . ' - ' . $model->getFullName(true),
    'controlbar' => [
        'button' => [
            'manage' => [
                'link' => ['contact-update', 'id' => $model->id],
            ],
        ],
        'template_add' => ['manage'],
    ],
];

Pjax::begin();
echo GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'item_default',
            'format' => 'html',
            'value' => function($model) {
                return CustomColumn::item_label($model, ['default']);
            }
        ],
        'name',
        'contact',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{default} {update} {delete}',
            'urlCreator' => function ($button, $item, $key, $index) use ($model) {
                if ($button === 'default') {
                    $url = ['contact-default', 'id' => $model->id, 'sid' => $item->id];
                    return $url;
                }
                if ($button === 'update') {
                    $url = ['contact-update', 'id' => $model->id, 'sid' => $item->id];
                    return $url;
                }
                if ($button === 'delete') {
                    $url = ['contact-delete', 'id' => $model->id, 'sid' => $item->id];
                    return $url;
                }
            },
        ],
    ],
]);
Pjax::end();
?>



