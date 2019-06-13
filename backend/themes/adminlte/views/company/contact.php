<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->params['panel'] = [
    'id' => 'tab',
    'tabs' => $tabs,
    'tabs_disabled' => false,
    'disabled' => false,
    'tools' => Html::a(Yii::t('backend/button', 'add'), ['contact_create', 'id' => $model->id], ['class' => 'text-muted', 'data-toggle' => 'modal', 'data-target' => '#modal-ajax']),
];
$this->params['controlbar'] = [
    'classname' => $company_type,
];

if ($company_type == 'cus') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'sell')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'customer'), 'url' => ['index']];
    $this->title = Yii::t('backend/menu', 'customer');
} else if ($company_type == 'sup') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'buy')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'supplier'), 'url' => ['index']];
    $this->title = Yii::t('backend/menu', 'supplier');
} else if ($company_type == 'man') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'manufacture')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'injector'), 'url' => ['index']];
    $this->title = Yii::t('backend/menu', 'injector');
}
if (!empty($model->id)) {
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['item', 'id' => $model->id]];
    $this->title = $model->name;
}

echo GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'columns' => [
        /*
          [
          'class' => 'yii\grid\RadioButtonColumn',
          'radioOptions' => function ($model) {
          return [
          'value' => $model['id'],
          'checked' => $model['id'] == 1
          ];
          }
          ], */
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        'contact',
        'memo',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{modal} {delete}',
            'urlCreator' => function ($button, $item, $key, $index) use ($model) {
                if ($button === 'modal') {
                    $url = ['contact_update', 'id' => $model->id, 'sid' => $item->id];
                    return $url;
                }
                if ($button === 'delete') {
                    $url = ['contact_delete', 'id' => $model->id, 'sid' => $item->id];
                    return $url;
                }
            },
        ],
    ],
]);
?>



