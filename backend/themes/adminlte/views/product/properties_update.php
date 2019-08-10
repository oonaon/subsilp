<?php

use yii\widgets\ActiveForm;
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
        'button' => [
            'cancel' => [
                'link' => ['properties', 'id' => $model->product_id],
            ],
        ],
        'template_add' => ['save', 'cancel'],
    ],
];

Pjax::begin();
$form = ActiveForm::begin(['id' => 'control-form', 'enableClientValidation' => false]);

if(!empty($model->name)){
    $data[$model->name]= ItemAlias::getLabel('product_properties',$model->name);
} else {
    $data = [];
    foreach (ItemAlias::getData('product_properties') as $key => $item) {
        if ($model->product->prop($key) === null) {
            $data[$key] = $item;
        }
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
Pjax::end();
?>
