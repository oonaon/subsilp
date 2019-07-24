<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\Unit;
use common\models\ItemAlias;

$form = ActiveForm::begin(['id' => 'form-modal']);
?>
<?= $form->errorSummary($model); ?>

<div class="row">
    <?= $form->field($model, 'label', ['options' => ['class' => 'col-xs-12 col-md-12']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'val', ['options' => ['class' => 'col-xs-12 col-md-12']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'sort_order', ['options' => ['class' => 'col-xs-12 col-md-12']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status', ['options' => ['class' => 'col-xs-12 col-md-12']])->dropDownList(ItemAlias::getData('status')) ?>
</div>

<?php
ActiveForm::end();
?>
