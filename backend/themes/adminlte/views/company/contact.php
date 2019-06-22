<?php

use yii\grid\GridView;
use yii\helpers\Html;
use common\components\CustomColumn;

$this->params['panel'] = [
    'id' => 'tab',
    'tabs' => $tabs,
    'tabs_disabled' => false,
    'disabled' => false,
    'tools' => Html::a(Yii::t('backend/button', 'add'), ['contact_create', 'id' => $model->id, '#' => 'modal-md'], ['class' => 'text-muted', 'data-toggle' => 'modal', 'data-target' => '#modal-ajax']),
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
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'item_default',
            'format' => 'html',
            'value' => function($model) {
                return CustomColumn::item_label($model,['default']);                
            }
        ],
        'name',
        'contact',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{default} {modal} {delete}',
            'urlCreator' => function ($button, $item, $key, $index) use ($model) {
                if ($button === 'default') {
                    $url = ['contact_default', 'id' => $model->id, 'sid' => $item->id];
                    return $url;
                }
                if ($button === 'modal') {
                    $url = ['contact_update', 'id' => $model->id, 'sid' => $item->id, '#' => 'modal-md'];
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



