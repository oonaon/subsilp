<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Source Messages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="source-message-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Source Message'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Data Table With Full Features</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
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
                        $cols = [
                            //  ['class' => 'yii\grid\SerialColumn'],
                            'id',
                            'category',
                            'message:ntext',
                        ];
                        foreach (Yii::$app->params['supportLanguages'] as $lang) {
                            echo $lang;
                            $cols[] = [
                                'attribute' => $lang,
                                'label' => strtoupper($lang),
                                'value' => function ($model) use ($lang) { return $model->getMessagesLanguage($lang); },
                            ];
                        }
                        $cols[] = [
                            'class' => 'yii\grid\ActionColumn',
                            // 'buttonOptions' => ['class' => 'btn btn-default'],
                            'template' => '<div class="btn-group btn-group-sm text-center" role="group"> {view} {update} {delete} </div>',
                            'contentOptions' => [
                                'noWrap' => true,
                                'style' => 'width: 120px;'
                            ],
                        ];

                        echo GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => $cols,
                        ]);
                        ?>
                    </div>
                </div>


            </div>
        </div>
        <!-- /.box-body -->
    </div>



</div>
