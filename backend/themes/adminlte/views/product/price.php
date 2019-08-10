<?php

use yii\grid\GridView;
use common\models\ItemAlias;
use yii\widgets\Pjax;
use common\components\HeadNavigator;

$this->params['header'] = HeadNavigator::header();
$this->params['breadcrumbs'] = HeadNavigator::breadcrumbs($model->code);

$this->params['panel'] = [
    'id' => 'tab',
    'tabs' => $tabs,
    'tabs_disabled' => false,
    'disabled' => false,
    'title' => $model->code,
    'controlbar' => [
        'button' => [
            'manage' => [
                'link' => ['prices-update', 'id' => $model->id],
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
        //['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'rank',
            'value' => function ($model) {
                return itemAlias::getLabel('company_rank', $model->rank);
            },
        // 'filter' => itemAlias::getData('product_rank'),
        ],
        'quantity',
        'price',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'urlCreator' => function ($button, $item, $key, $index) use ($model) {
                if ($button === 'update') {
                    $url = ['prices-update', 'id' => $model->id, 'sid' => $item->id];
                    return $url;
                }
                if ($button === 'delete') {
                    if ($item->item_fix) {
                        return false;
                    } else {
                        $url = ['prices-delete', 'id' => $model->id, 'sid' => $item->id];
                        return $url;
                    }
                }
            },
        ],
    ],
]);
Pjax::end();
?>



