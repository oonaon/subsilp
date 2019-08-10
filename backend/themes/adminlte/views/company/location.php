<?php

use yii\grid\GridView;
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
                'link' => ['location-update', 'id' => $model->id],
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
                return CustomColumn::item_label($model, ['fix', 'default']);
            }
        ],
        [
            'attribute' => 'address',
            'format' => 'html',
            'value' => function($model) {
                return $model->getFullAddress();
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{default} {update} {delete}',
            'urlCreator' => function ($button, $item, $key, $index) use ($model) {
                if ($button === 'default') {
                    $url = ['location-default', 'id' => $model->id, 'sid' => $item->id];
                    return $url;
                }
                if ($button === 'update') {
                    $url = ['location-update', 'id' => $model->id, 'sid' => $item->id];
                    return $url;
                }
                if ($button === 'delete') {
                    if ($item->item_fix) {
                        return false;
                    } else {
                        $url = ['location-delete', 'id' => $model->id, 'sid' => $item->id];
                        return $url;
                    }
                }
            },
        ],
    ],
]);
Pjax::end();
?>
