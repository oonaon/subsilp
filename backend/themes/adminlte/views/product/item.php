<?php

use yii\helpers\Url;
use common\models\ItemAlias;
use common\models\Unit;
use common\models\ProductCategory;
use kartik\select2\Select2;
use common\components\Area;
use yii\widgets\ActiveForm;

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'sell')];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'customer'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'id' => $model->id]];
$this->params['title'] = Yii::t('backend/menu', 'product');

$action_id = Yii::$app->controller->action->id;
if ($action_id == 'view') {
    $button = ['update'];
} else if ($action_id == 'update') {
    $button = ['save', 'cancel'];
} else if ($action_id == 'create') {
    $button = ['save', 'cancel'];
}

$this->params['panel'] = [
    'tabs' => $tabs,
    'tabs_disabled' => in_array($action_id, ['create', 'update']) ? true : false,
    'disabled' => in_array($action_id, ['create', 'update']) ? false : true,
    'title' => empty($model->code) ? Yii::t('backend/tab', 'add_new') : $model->code,
    'button' => $button,
];

/*[
        'index', 'search', 'add', 'delete',
        'first', 'previous', 'next', 'last',
    ]*/

$form = ActiveForm::begin(['id' => 'control-form']);
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
    $form->field($model, 'org', ['options' => ['class' => 'col-xs-12 col-md-4']])->widget(Select2::classname(), [
        'data' => ItemAlias::getData('org'),
        'options' => ['multiple' => true],
        'disabled' => $this->params['panel']['disabled'],
    ]);
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

<?php ActiveForm::end(); ?>
