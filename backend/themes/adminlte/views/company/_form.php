<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$action_id = Yii::$app->controller->action->id;
$disabled = false;
if ($action_id == 'view') {
    $disabled = true;
}
?>

<div class="page-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'control-form',
    ]);
    ?>
    <fieldset <?= ($disabled ? 'disabled="disabled"' : '') ?>>

        <div class="row">
            <?= $form->field($model, 'code', ['options' => ['class' => 'col-xs-2']])->textInput(['maxlength' => true]) ?>
        </div>

        <div class="row">   
            <?= $form->field($model, 'prefix', ['options' => ['class' => 'col-xs-2']])->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'name', ['options' => ['class' => 'col-xs-4']])->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'suffix', ['options' => ['class' => 'col-xs-2']])->textInput(['maxlength' => true]) ?>
        </div>

        <div class="row">
            <?= $form->field($model, 'tax', ['options' => ['class' => 'col-xs-4']])->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'branch', ['options' => ['class' => 'col-xs-2']])->textInput() ?>
        </div>

        <div class="row">
            <?= $form->field($model, 'address', ['options' => ['class' => 'col-xs-12']])->textInput(['maxlength' => true]) ?>
        </div>

        <div class="row">
            <?= $form->field($model, 'subdistrict', ['options' => ['class' => 'col-xs-3']])->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'district', ['options' => ['class' => 'col-xs-3']])->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'province', ['options' => ['class' => 'col-xs-3']])->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'postcode', ['options' => ['class' => 'col-xs-3']])->textInput(['maxlength' => true]) ?>
        </div>

        <div class="row">
            <?= $form->field($model, 'tel', ['options' => ['class' => 'col-xs-3']])->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'fax', ['options' => ['class' => 'col-xs-3']])->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'email', ['options' => ['class' => 'col-xs-3']])->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'website', ['options' => ['class' => 'col-xs-3']])->textInput(['maxlength' => true]) ?>
        </div>

        <div class="row">
            <?= $form->field($model, 'payment', ['options' => ['class' => 'col-xs-6']])->textarea(['rows' => 6]) ?>
            <?= $form->field($model, 'credit', ['options' => ['class' => 'col-xs-2']])->textInput() ?>
        </div>

        <div class="row">
            <?= $form->field($model, 'memo', ['options' => ['class' => 'col-xs-2']])->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'salesman', ['options' => ['class' => 'col-xs-2']])->textInput() ?>

            <?= $form->field($model, 'transport', ['options' => ['class' => 'col-xs-2']])->textInput() ?>

            <?= $form->field($model, 'rank', ['options' => ['class' => 'col-xs-3']])->textInput(['maxlength' => true]) ?>
            
            <?= $form->field($model, 'status', ['options' => ['class' => 'col-xs-3']])->textInput(['maxlength' => true]) ?>
        </div>





    </fieldset>
    <?php ActiveForm::end(); ?>

</div>
