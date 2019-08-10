<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\Unit;
use common\models\ItemAlias;
use yii\widgets\Pjax;
use common\components\HeadNavigator;

$this->params['header'] = HeadNavigator::header();
$this->params['breadcrumbs'] = HeadNavigator::breadcrumbs($model->product->code);

$this->params['panel'] = [
    'id' => 'tab',
    'tabs' => $tabs,
    'tabs_disabled' => false,
    'disabled' => false,
    'title' => $model->product->code,
    'controlbar' => [
        'button'=>[
            'cancel' => [
                'link' => ['prices', 'id' => $model->product_id],
            ],
        ],
        'template_add' => ['save','cancel'],
    ],
];
Pjax::begin();
$form = ActiveForm::begin(['id' => 'control-form', 'enableClientValidation' => false]);


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
Pjax::end();
?>
