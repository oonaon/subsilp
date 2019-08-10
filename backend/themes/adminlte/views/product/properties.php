<?php

use yii\grid\GridView;
use yii\helpers\Html;
use common\components\CustomColumn;
use common\components\Button;
use common\models\Unit;
use common\models\ItemAlias;
use yii\widgets\Pjax;
use common\components\HeadNavigator;

$this->params['header'] = HeadNavigator::header();
$this->params['breadcrumbs'] = HeadNavigator::breadcrumbs($model->code);

$this->params['panel'] = [
    'id' => 'tab',
    'tabs' => $tabs,
    'tabs_disabled' => false,
    'disabled' => false,
    'title' => $model->code,
    'controlbar' => [
        'button' => [
            'manage' => [
                'link' => ['properties-update', 'id' => $model->id],
            ],
        ],
        'template_add' => ['manage'],
    ],
];
Pjax::begin();
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
            'template' => '{update} {delete}',
            'urlCreator' => function ($button, $item, $key, $index) use ($model) {
                if ($button === 'update') {
                    $url = ['properties-update', 'id' => $model->id, 'sid' => $item->id];
                    return $url;
                }
                if ($button === 'delete') {
                    if ($model->checkPrimaryProperties($item->name)) {
                        return false;
                    } else {
                        $url = ['properties-delete', 'id' => $model->id, 'sid' => $item->id];
                        return $url;
                    }
                }
            },
        ],
    ],
]);
Pjax::end();
?>



