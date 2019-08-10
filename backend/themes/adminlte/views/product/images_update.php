<?php

use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\components\HeadNavigator;

$this->params['header'] = HeadNavigator::header();
$this->params['breadcrumbs'] = HeadNavigator::breadcrumbs($model->code);

$this->params['panel'] = [
    'id' => 'tab',
    'tabs' => $tabs,
    'tabs_disabled' => false,
    'disabled' => false,
    'title' => $model->code,
    'controlbar' => [
        'button' => [
            'cancel' => [
                'link' => ['images', 'id' => $model->id],
            ],
        ],
        'template_add' => ['save', 'cancel'],
    ],
];

Pjax::begin();
$form = ActiveForm::begin(['id' => 'control-form', 'enableClientValidation' => false, 'options' => ['enctype' => 'multipart/form-data']]);
?>
<?= $form->errorSummary($model); ?>

<div class="row">
<?= $form->field($model, 'images', ['options' => ['class' => 'col-xs-12']])->file(['multiple' => true, 'accept' => 'image/*']) ?>
</div>

<?php
ActiveForm::end();
Pjax::end();
?>
