<?php

use yii\helpers\Url;
use common\models\ItemAlias;
use common\components\Area;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;

$controller = Yii::$app->controller->id;
if ($controller == 'quotation') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'sell')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'customer'), 'url' => ['index']];
    $this->params['title'] = Yii::t('backend/menu', 'customer');
} else if ($controller == 'saleorder') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'buy')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'supplier'), 'url' => ['index']];
    $this->params['title'] = Yii::t('backend/menu', 'supplier');
} else if ($controller == 'invoice') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'manufacture')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'injector'), 'url' => ['index']];
    $this->params['title'] = Yii::t('backend/menu', 'injector');
}
if (!empty($model->id)) {
    $this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'id' => $model->id]];
}

$action_id = Yii::$app->controller->action->id;
if ($action_id == 'view') {
    $button = ['update'];
} else if ($action_id == 'update') {
    $button = ['save', 'cancel'];
} else if ($action_id == 'create') {
    $button = ['save', 'cancel'];
}

$this->params['panel'] = [
    'id' => 'tab',
    'tabs' => $tabs,
    'tabs_disabled' => in_array(Yii::$app->controller->action->id, ['create', 'update']) ? true : false,
    'disabled' => in_array(Yii::$app->controller->action->id, ['create', 'update']) ? false : true,
    'title' => empty($model->id) ? Yii::t('backend/tab', 'add_new') : $model->code,
    'controlbar' => [
        'template_add' => $button,
    ],
];
Pjax::begin();
$form = ActiveForm::begin(['id' => 'control-form']);
echo $form->errorSummary($model);
?>

<div class="row">
    <?= $form->field($model, 'code', ['options' => ['class' => 'col-xs-6 col-md-3']])->code(['maxlength' => true]) ?>
    <?= $form->field($model, 'date', ['options' => ['class' => 'col-xs-6 col-md-3']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'company_id', ['options' => ['class' => 'col-xs-6 col-md-3']])->selectAjax(['company', 'type' => 'cus'], 3, ['disabled' => $this->params['panel']['disabled']]) ?>

    <?= $form->field($model, 'reference', ['options' => ['class' => 'col-xs-6 col-md-3']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'remark', ['options' => ['class' => 'col-xs-6 col-md-3']])->textInput(['maxlength' => true]) ?>
</div>

<div class="row">
    <div class="col-sm-12">
        <?php
        if (!empty($model->company)) {
            echo $model->company->name;
            echo '<br>';
            echo $model->company->address;
        }
        ?>
    </div>
</div>

<?php
ActiveForm::end();
Pjax::end();
?>
