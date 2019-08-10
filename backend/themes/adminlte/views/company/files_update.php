<?php

use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\components\HeadNavigator;

$this->params['header'] = HeadNavigator::header();
$this->params['breadcrumbs'] = HeadNavigator::breadcrumbs($model->getFullName(false));

$this->params['panel'] = [
    'id' => 'tab',
    'tabs' => $tabs,
    'tabs_disabled' => false,
    'disabled' => false,
    'title' => $model->code . ' - ' . $model->getFullName(true),
    'controlbar' => [
        'button' => [
            'cancel' => [
                'link' => ['files', 'id' => $model->id],
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
<?= $form->field($model, 'files', ['options' => ['class' => 'col-xs-12']])->file(['multiple' => true, 'accept' => 'image/*']) ?>
</div>

<?php
ActiveForm::end();
Pjax::end();
?>
