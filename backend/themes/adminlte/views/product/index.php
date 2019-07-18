<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\ItemAlias;
use common\models\ProductCategory;
use common\components\CustomColumn;
use common\components\ControlBar;

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'sell')];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'product'), 'url' => ['index']];
$this->params['title']=Yii::t('backend/menu', 'product');

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