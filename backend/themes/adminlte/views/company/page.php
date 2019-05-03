<?php

use yii\helpers\Html;
use common\components\ControlBar;
use yii\grid\GridView;
use yii\widgets\Pjax;


$action_id=Yii::$app->controller->action->id;
//$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Companies'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;


//\yii\web\YiiAsset::register($this);
?>
<div class="page-layout">

    <?= ControlBar::widget(['classname' => 'Company']) ?>  

    <div class="box box-solid">
        <div class="box-body">
            <?php
            if ($action_id=='index') {
                Pjax::begin();
                 
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        //['class' => 'yii\grid\SerialColumn'],
                        //'id',
                        //'org',
                        'code',
                        'prefix',
                        'name',
                        'suffix',
                        //'branch',
                        'tel',
                        'tax',
                        //'fax',
                        //'email:email',
                        //'website',
                        //'address',
                        //'subdistrict',
                        //'district',
                        //'province',
                        //'postcode',
                        //'language',
                        //'credit',
                        //'payment:ntext',
                        //'memo:ntext',
                        //'salesman',
                        //'transport',
                        //'rank',
                        //'status',
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]);
                Pjax::end();
            } else {
                echo $this->render('_form', [
                    'model' => $model,
                ]);
            }
            ?>

        </div>
    </div>

</div>
