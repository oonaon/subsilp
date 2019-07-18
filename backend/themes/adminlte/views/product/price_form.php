<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\Unit;
use common\models\ItemAlias;

$form = ActiveForm::begin([
            'id' => 'form-modal',
        ]);

$data = [];
foreach (ItemAlias::getData('company_rank') as $key => $item) {
    if ($model->item_fix || $key !== 'D') {
        $data[$key] = $item;
    }
}
if ($model->item_fix) {
    $disabled = true;
} else {
    $disabled = false;
}
?>
<?= $form->errorSummary($model); ?>

<div class="row">
    <?= $form->field($model, 'rank', ['options' => ['class' => 'col-xs-12 col-md-12']])->dropDownList($data, ['disabled' => $disabled]) ?>
    <?= $form->field($model, 'quantity', ['options' => ['class' => 'col-xs-12 col-md-12']])->textInput(['maxlength' => true,'disabled' => $disabled]) ?>
    <?= $form->field($model, 'price', ['options' => ['class' => 'col-xs-12 col-md-12']])->textInput(['maxlength' => true]) ?>
</div>

<?php
ActiveForm::end();
?>
