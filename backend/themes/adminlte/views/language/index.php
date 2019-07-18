<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\MessageSource;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
$this->params['title'] = Yii::t('backend/menu', 'language');
?>


<div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
    <div class="row">
        <div class="col-sm-12">
            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    //  ['class' => 'yii\grid\SerialColumn'],
                    //'id',
                    [
                        'attribute' => 'category',
                        'filter' => ArrayHelper::map(MessageSource::find()->all(), 'category', 'category'),
                    ],
                    'message:ntext',
                    [
                        'attribute' => 'lang_th',
                        'label' => Yii::t('common/general', 'thai'),
                        'value' => function ($model) {
                            return $model->getMessageTranslate('th');
                        },
                    ], [
                        'attribute' => 'lang_en',
                        'label' => Yii::t('common/general', 'english'),
                        'value' => function ($model) {
                            return $model->getMessageTranslate('en');
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>

