<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
            'id' => 'form-modal',
        ]);
?>
<?= $form->errorSummary($model_contact); ?>

<div class="row">
    <?= $form->field($model_contact, 'name', ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model_contact, 'contact', ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['maxlength' => true]) ?>
</div>

<?php
ActiveForm::end();
?>
