<?php

use yii\grid\GridView;
use common\models\ItemAlias;
use common\components\CustomColumn;
use yii\widgets\Pjax;
use common\components\HeadNavigator;

$this->params['header'] = HeadNavigator::header();
$this->params['breadcrumbs'] = HeadNavigator::breadcrumbs();

Pjax::begin();
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        //['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'org',
            'format' => 'html',
            'filter' => itemAlias::getData('org'),
            'value' => function($model) {
                return CustomColumn::org($model->org);
            }
        ],
        'code',
        [
            'attribute' => 'status',
            'format' => 'html',
            'filter' => itemAlias::getData('status'),
            'value' => function($model) {
                return CustomColumn::status($model->status);
            }
        ],
        ['class' => 'yii\grid\ActionColumn'],
    ],
]);
Pjax::end();
?>