<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\Unit;
use common\models\ItemAlias;

$form = ActiveForm::begin([
            'id' => 'form-modal',
        ]);

$data = [];
foreach (ItemAlias::getData('product_properties') as $key => $item) {
    if ($product->properties($key) === null) {
        $data[$key] = $item;
    }
}
?>
<?= $form->errorSummary($model); ?>

<div class="row">
    <?= $form->field($model, 'name', ['options' => ['class' => 'col-xs-12 col-md-12']])->dropDownList($data) ?>
    <?= $form->field($model, 'val', ['options' => ['class' => 'col-xs-12 col-md-12']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'unit_id', ['options' => ['class' => 'col-xs-12 col-md-12']])->dropDownList(Unit::getData()) ?>
</div>

<?php
ActiveForm::end();
?>
