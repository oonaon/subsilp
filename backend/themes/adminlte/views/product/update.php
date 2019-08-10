<?php

use yii\helpers\Url;
use common\models\ItemAlias;
use common\models\Unit;
use common\models\ProductCategory;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\components\HeadNavigator;

$this->params['header'] = HeadNavigator::header();
$this->params['breadcrumbs'] = HeadNavigator::breadcrumbs($model->code);

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
    'title' => empty($model->code) ? Yii::t('backend/tab', 'add_new') : $model->code,
    'controlbar' => [
        'template_add' => $button,
    ],
];

Pjax::begin();

$form = ActiveForm::begin(['id' => 'control-form', 'enableClientValidation' => false]);
?>
<?= $form->errorSummary($model); ?>

<div class="row">
    <?= $form->field($model, 'code', ['options' => ['class' => 'col-xs-12 col-md-4']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'caption', ['options' => ['class' => 'col-xs-12 col-md-8']])->textInput(['maxlength' => true]) ?>

</div>

<div class="row">
    <?= $form->field($model, 'category_id', ['options' => ['class' => 'col-xs-12 col-md-4']])->dropDownList(ProductCategory::getData()) ?>
    <?= $form->field($model, 'kind', ['options' => ['class' => 'col-xs-12 col-md-4']])->dropDownList(ItemAlias::getData('product_kind')) ?>
    <?=
    $form->field($model, 'org', ['options' => ['class' => 'col-xs-12 col-md-4']])->multiple(ItemAlias::getData('org'), ['disabled' => $this->params['panel']['disabled']]);
    ?>
</div>

<div class="row">
    <?= $form->field($model, 'detail', ['options' => ['class' => 'col-xs-12']])->textarea(['rows' => 3]) ?>
</div>

<div class="row">    
    <?= $form->field($model, 'unit_id', ['options' => ['class' => 'col-xs-6 col-md-3']])->dropDownList(Unit::getData()) ?>
    <?= $form->field($model, 'status', ['options' => ['class' => 'col-xs-6 col-md-3']])->dropDownList(ItemAlias::getData('status')) ?>
</div>

<div class="row">
    <?= $form->field($model, 'alias', ['options' => ['class' => 'col-xs-12']])->textarea(['rows' => 3]) ?>
    <?= $form->field($model, 'memo', ['options' => ['class' => 'col-xs-12']])->textarea(['rows' => 3]) ?>
</div>

<?php
ActiveForm::end();
Pjax::end();
?>
