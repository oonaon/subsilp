<?php

use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\components\HeadNavigator;

$this->params['header'] = HeadNavigator::header();
$this->params['breadcrumbs'] = HeadNavigator::breadcrumbs($model->company->getFullName(false));

$this->params['panel'] = [
    'id' => 'tab',
    'tabs' => $tabs,
    'tabs_disabled' => false,
    'disabled' => false,
    'title' => $model->company->code . ' - ' . $model->company->getFullName(true),
    'controlbar' => [
        'button'=>[
            'cancel' => [
                'link' => ['contact', 'id' => $model->company->id],
            ],
        ],
        'template_add' => ['save','cancel'],
    ],
];

Pjax::begin();
$form = ActiveForm::begin(['id' => 'control-form', 'enableClientValidation' => false]);
?>
<?= $form->errorSummary($model); ?>

<div class="row">
    <?= $form->field($model, 'name', ['options' => ['class' => 'col-xs-12 col-md-12']])->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'contact', ['options' => ['class' => 'col-xs-12 col-md-12']])->textInput(['maxlength' => true]) ?>
</div>

<?php
ActiveForm::end();
Pjax::end();
?>
