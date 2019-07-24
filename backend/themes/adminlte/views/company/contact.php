<?php
use yii\grid\GridView;
use yii\helpers\Html;
use common\components\CustomColumn;

$controller=Yii::$app->controller->id;
if ($controller == 'customer') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'sell')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'customer'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['item', 'id' => $model->id]];
    $this->params['title'] = Yii::t('backend/menu', 'customer');
} else if ($controller == 'supplier') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'buy')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'supplier'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['item', 'id' => $model->id]];
    $this->params['title'] = Yii::t('backend/menu', 'supplier');
} else if ($controller == 'manufacturer') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'manufacture')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'injector'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['item', 'id' => $model->id]];
    $this->params['title'] = Yii::t('backend/menu', 'injector');
}

$this->params['panel'] = [
    'id' => 'tab',
    'tabs' => $tabs,
    'tabs_disabled' => false,
    'disabled' => false,
    'title'=>$model->name,
    'controlbar' => [
        'button' => [
            'add_update' => [
                'link' => ['contact_create', 'id' => $model->id, '#' => 'modal-md'],
                'modal' => 'modal-ajax',
            ],
        ],
        'template_add' => ['add_update'],
    ],
];

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



