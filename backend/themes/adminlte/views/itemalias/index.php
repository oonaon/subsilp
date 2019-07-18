<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\ItemAlias;
use common\components\CustomColumn;
use common\components\ControlBar;

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'sell')];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'customer'), 'url' => ['index']];
$this->title = Yii::t('backend/menu', 'customer');

$this->params['controlbar'] = [
    'classname' => 'itemalias',
];

Pjax::begin();
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        //['class' => 'yii\grid\SerialColumn'],

        'val',
        'label',
        /*
          [
          'attribute' => 'kind',
          'value' => function ($model) {
          return itemAlias::getLabel('company_kind', $model->kind);
          },
          'filter' => itemAlias::getData('company_kind'),
          ],
          'name',
          'tel',
          'tax',
          [
          'attribute' => 'status',
          'format' => 'html',
          'filter' => itemAlias::getData('status'),
          'value' => function($model) {
          return CustomColumn::status($model->status);
          }
          ],
         * 
         */
        ['class' => 'yii\grid\ActionColumn'],
    ],
]);
Pjax::end();
?>