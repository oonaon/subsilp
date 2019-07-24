<?php

use yii\grid\GridView;
use yii\helpers\Html;
use common\components\CustomColumn;
use common\components\Button;
use common\models\Unit;
use common\models\ItemAlias;

$this->params['title'] = Yii::t('backend/menu', 'product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'sell')];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'customer'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['item', 'id' => $model->id]];
$this->params['panel'] = [
    'tabs' => $tabs,
    'tabs_disabled' => false,
    'disabled' => false,
    'title' => $model->code,
    'controlbar' => [
        'button' => [
            'add_update' => [
                'link' => ['properties_create', 'id' => $model->id, '#' => 'modal-md'],
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
        //['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'name',
            'value' => function ($model) {
                return itemAlias::getLabel('product_properties', $model->name);
            },
        //  'filter' => itemAlias::getData('product_properties'),
        ],
        'val',
        [
            'attribute' => 'unit_id',
            'value' => function ($model) {
                return Unit::getLabel($model->unit_id);
            },
        //   'filter' => Unit::getData(),
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{modal} {delete}',
            'urlCreator' => function ($button, $item, $key, $index) use ($model) {
                if ($button === 'modal') {
                    $url = ['properties_update', 'id' => $model->id, 'sid' => $item->id, '#' => 'modal-md'];
                    return $url;
                }
                if ($button === 'delete') {
                    if ($model->checkPrimaryProperties($item->name)) {
                        return false;
                    } else {
                        $url = ['properties_delete', 'id' => $model->id, 'sid' => $item->id];
                        return $url;
                    }
                }
            },
        ],
    ],
]);
?>



