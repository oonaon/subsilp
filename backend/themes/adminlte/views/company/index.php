<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\ItemAlias;
use common\components\CustomColumn;

$controller = Yii::$app->controller->id;
if ($controller == 'customer') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'sell')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'customer'), 'url' => ['index']];
    $this->params['title'] = Yii::t('backend/menu', 'customer');
} else if ($controller == 'supplier') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'buy')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'supplier'), 'url' => ['index']];
    $this->params['title'] = Yii::t('backend/menu', 'supplier');
} else if ($controller == 'manufacturer') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'manufacture')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'injector'), 'url' => ['index']];
    $this->params['title'] = Yii::t('backend/menu', 'injector');
}

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
        ['class' => 'yii\grid\ActionColumn'],
    ],
]);
Pjax::end();
?>