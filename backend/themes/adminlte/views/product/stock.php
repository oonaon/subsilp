<?php

use yii\grid\GridView;
use yii\helpers\Html;
use common\components\CustomColumn;
use common\models\Unit;
use common\models\ItemAlias;

$this->params['panel'] = [
    'tabs' => $tabs,
    'tabs_disabled' => false,
    'disabled' => false,
    'title' => $model->code,
];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'sell')];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'customer'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['item', 'id' => $model->id]];
$this->params['title'] = Yii::t('backend/menu', 'product');

$colors = Yii::$app->params['color'];
?>
<div class="row">

    <?php
    foreach ($stocks as $color => $stock) {
        ?>
        <div class="col-md-3">


            <div class="box box-default box-solid" style="border-color:<?= $colors[$color]['solid'] ?>;">
                <div class="info-box" style="margin-bottom:0px; color:<?= $colors[$color]['text'] ?>; background-color:<?= $colors[$color]['solid'] ?>;">
                    <span class="info-box-icon"><?= $color ?></span>

                    <div class="info-box-content">
                        <span class="info-box-text"><?= Yii::t('common/itemalias', 'color_' . strtolower($color)) ?></span>
                        <span class="info-box-number">41,410</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 70%"></div>
                        </div>
                        <span class="progress-description">
                            70% Increase in 30 Days
                        </span>
                    </div>

                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-stacked">
                        <?php
                        foreach ($stock as $item) {
                            ?>
                            <li><a href="#"><?= strtoupper($item->name) ?> <span class="pull-right"><?= $item->quantity ?></span></a></li>
                                    <?php
                                }
                                ?>

                    </ul>
                </div>
            </div>


        </div>
        <?php
    }
    ?>



</div>



