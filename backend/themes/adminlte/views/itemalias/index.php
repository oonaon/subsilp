<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\ItemAlias;
use common\components\CustomColumn;

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'sell')];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'customer'), 'url' => ['index']];
$this->params['title'] = Yii::t('backend/menu', 'itemalias');

$cats = [];
foreach ($category as $item) {
    $cats[] = [
        'label' => $item['category'],
        'link' => ['index', 'category' => $item['category']],
        'active' => ($item['category'] == $select_category) ? true : false,
    ];
}
$this->params['panel'] = [
    'category' => $cats,
    'controlbar' => [
        'button' => [
            'add_update' => [
                'link' => ['item_create', 'category' => $select_category, '#' => 'modal-md'],
                'modal' => 'modal-ajax',
            ],
        ],
        'template' => ['add_update'],
    ],
];

Pjax::begin();
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        //['class' => 'yii\grid\SerialColumn'],
        'sort_order',
        'label',
        'val',
        [
            'attribute' => 'label',
            'format' => 'html',
            'value' => function($model) {
                return Yii::t('common/itemalias', $model->label);
            }
        ],
        [
            'attribute' => 'label',
            'format' => 'html',
            'value' => function($model) {
                return Yii::t('common/itemalias', $model->label, [], 'en');
            }
        ],
        [
            'attribute' => 'status',
            'format' => 'html',
            'value' => function($model) {
                return CustomColumn::status($model->status);
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{modal} {delete}',
            'urlCreator' => function ($button, $item, $key, $index) {

                if ($button === 'modal') {
                    $url = ['item_update', 'id' => $item->id, '#' => 'modal-sm'];
                    return $url;
                }
                if ($button === 'delete') {
                    $url = ['item_delete', 'id' => $item->id];
                    return $url;
                }
            },
        ],
    ],
]);
Pjax::end();
?>