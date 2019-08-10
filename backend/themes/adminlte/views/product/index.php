<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\ItemAlias;
use common\models\ProductCategory;
use common\components\CustomColumn;
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
        /*
          [
          'attribute' => 'kind',
          'value' => function ($model) {
          return itemAlias::getLabel('product_kind', $model->kind);
          },
          'filter' => itemAlias::getData('product_kind'),
          ],
         */
        [
            'attribute' => 'category_id',
            'value' => function ($model) {
                return productCategory::getLabel($model->category_id);
            },
            'filter' => productCategory::getData(),
        ],
        [
            'attribute' => 'status',
            'format' => 'html',
            'value' => function($model) {
                return CustomColumn::status($model->status);
            }
        ],
        //'caption',
        /*
          [
          'attribute' => 'status',
          'format' => 'html',
          'filter' => itemAlias::getData('status'),
          'value' => function($model) {
          return CustomColumn::status($model->status);
          }
          ],
         */
        ['class' => 'yii\grid\ActionColumn'],
    ],
]);
Pjax::end();
?>