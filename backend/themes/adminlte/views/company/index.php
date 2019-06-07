<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\ItemAlias;
use common\components\CustomColumn;
use common\components\ControlBar;

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
?>
<div class="page-layout">

    <?= ControlBar::widget(['classname' => $company_type]) ?>  

    <div class="box box-solid">
        <div class="box-body">

            <?php
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
        </div>
    </div>
</div>