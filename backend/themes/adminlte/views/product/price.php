<?php

use yii\grid\GridView;
use yii\helpers\Html;
use common\components\CustomColumn;
use common\components\Button;
use common\models\Unit;
use common\models\ItemAlias;

$this->params['panel'] = [
    'tabs' => $tabs,
    'tabs_disabled' => false,
    'disabled' => false,
    'title' => $model->code,
    'button' => [
        'add_update' => [
            'link' => ['prices_create', 'id' => $model->id, '#' => 'modal-md'],
            'modal' => 'modal-ajax',
        ],
    ],
];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'sell')];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'customer'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['item', 'id' => $model->id]];
$this->params['title'] = Yii::t('backend/menu', 'product');


echo GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'columns' => [
        //['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'rank',
            'value' => function ($model) {
                return itemAlias::getLabel('company_rank', $model->rank);
            },
        // 'filter' => itemAlias::getData('product_rank'),
        ],
        'quantity',
        'price',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{modal} {delete}',
            'urlCreator' => function ($button, $item, $key, $index) use ($model) {
                if ($button === 'modal') {
                    $url = ['prices_update', 'id' => $model->id, 'sid' => $item->id, '#' => 'modal-md'];
                    return $url;
                }
                if ($button === 'delete') {
                    if ($item->item_fix) {
                        return false;
                    } else {
                        $url = ['prices_delete', 'id' => $model->id, 'sid' => $item->id];
                        return $url;
                    }
                }
            },
        ],
    ],
]);
?>



