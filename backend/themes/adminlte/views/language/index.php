<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\MessageSource;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend/menu', 'language');
$this->params['breadcrumbs'][] = $this->title;
?>


<div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
    <?php /*
      <div class="row">
      <div class="col-sm-6">
      <div class="dataTables_length" id="example1_length">
      <label>Show
      <select name="example1_length" aria-controls="example1" class="form-control input-sm">
      <option value="10">10</option>
      <option value="25">25</option>
      <option value="50">50</option>
      <option value="100">100</option>
      </select> entries
      </label>
      </div>
      </div>
      <div class="col-sm-6">
      <div id="example1_filter" class="dataTables_filter">
      <label>Search:
      <input type="search" class="form-control input-sm" placeholder="" aria-controls="example1">
      </label>
      </div>
      </div>
      </div>
     */ ?>

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

