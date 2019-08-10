<?php

use common\models\ItemAlias;
use common\components\CActiveForm;
use yii\widgets\Pjax;
use common\components\HeadNavigator;

$this->params['header'] = HeadNavigator::header();
$this->params['breadcrumbs'] = HeadNavigator::breadcrumbs($model->getFullName(false));

$action_id = Yii::$app->controller->action->id;
if ($action_id == 'view') {
    $button = ['update'];
} else if ($action_id == 'update') {
    $button = ['save', 'cancel'];
}

$this->params['panel'] = [
    'id' => 'tab',
    'tabs' => $tabs,
    'tabs_disabled' => $action_id == 'update' ? true : false,
    'disabled' => $action_id == 'update' ? false : true,
    'title' => empty($model->name) ? Yii::t('backend/tab', 'add_new') : $model->code . ' - ' . $model->getFullName(true),
    'controlbar' => [
        'template_add' => $button,
    ],
];

Pjax::begin();
$form = CActiveForm::begin(['id' => 'control-form', 'enableClientValidation' => false]);
?>
<?= $form->errorSummary($model); ?>

<div class="row">
    <?= $form->field($model, 'code', ['options' => ['class' => 'col-xs-6 col-md-3']])->textInput(['maxlength' => true]) ?>
    <?=
    $form->field($model, 'org', ['options' => ['class' => 'col-xs-12 col-md-4']])->multiple(ItemAlias::getData('org'), ['disabled' => $this->params['panel']['disabled']]);
    ?>
    <?=
    $form->field($model, 'type', ['options' => ['class' => 'col-xs-12 col-md-4']])->multiple(ItemAlias::getData('company_type'), ['disabled' => $this->params['panel']['disabled']]);
    ?>
</div>

<div class="row">
    <?= $form->field($model, 'kind', ['options' => ['class' => 'col-xs-6 col-md-3']])->dropDownList(ItemAlias::getData('company_kind')) ?>
    <?= $form->field($model, 'name', ['options' => ['class' => 'col-xs-12 col-md-9']])->textInput(['maxlength' => true]) ?>
</div>

<div class="row">
    <?= $form->field($model, 'tax', ['options' => ['class' => 'col-xs-8 col-md-6']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'branch', ['options' => ['class' => 'col-xs-4 col-md-3']])->textInput() ?>
</div>

<div class="row">
    <?= $form->field($model, 'district', ['options' => ['class' => 'col-xs-12 col-md-12']])->selectAjax(['postcode'], 5, ['disabled' => $this->params['panel']['disabled']])->label(Yii::t('common/model', 'postcode')) ?>
    <?= $form->field($model, 'address', ['options' => ['class' => 'col-xs-12 col-md-12']])->textarea(['rows' => 2]) ?>
</div>

<div class="row">
    <?= $form->field($model, 'tel', ['options' => ['class' => 'col-xs-6 col-md-3']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'fax', ['options' => ['class' => 'col-xs-6 col-md-3']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email', ['options' => ['class' => 'col-xs-6 col-md-3']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'website', ['options' => ['class' => 'col-xs-6 col-md-3']])->textInput(['maxlength' => true]) ?>
</div>

<div class="row">
    <?= $form->field($model, 'salesman', ['options' => ['class' => 'col-xs-6 col-md-3']])->textInput() ?>
    <?= $form->field($model, 'rank', ['options' => ['class' => 'col-xs-6 col-md-3']])->dropDownList(ItemAlias::getData('company_rank')) ?>
    <?= $form->field($model, 'status', ['options' => ['class' => 'col-xs-6 col-md-3']])->dropDownList(ItemAlias::getData('status')) ?>
</div>

<div class="row">
    <?= $form->field($model, 'payment', ['options' => ['class' => 'col-xs-12']])->textarea(['rows' => 3]) ?>
    <?= $form->field($model, 'credit', ['options' => ['class' => 'col-xs-12']])->textInput() ?>
</div>

<div class="row">
    <?= $form->field($model, 'transport', ['options' => ['class' => 'col-xs-12']])->dropDownList(ItemAlias::getData('transport')) ?>
    <?= $form->field($model, 'transport_note', ['options' => ['class' => 'col-xs-12']])->textarea(['rows' => 3]) ?>
</div>

<div class="row">
    <?= $form->field($model, 'memo', ['options' => ['class' => 'col-xs-12']])->textarea(['rows' => 3]) ?>
</div>

<?php
CActiveForm::end();
Pjax::end();
?>
