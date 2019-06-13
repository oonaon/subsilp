<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\ItemAlias;
use common\models\AreaCode;
use kartik\select2\Select2;
use kartik\widgets\DepDrop;
use backend\controllers\AreaController;

$this->params['panel'] = [
    'id' => 'tab',
    'tabs' => $tabs,
    'tabs_disabled' => in_array(Yii::$app->controller->action->id, ['item_create', 'item_update']) ? true : false,
    'disabled' => in_array(Yii::$app->controller->action->id, ['item_create', 'item_update']) ? false : true,
    'tools' => false,
];
$this->params['controlbar'] = [
    'classname' => $company_type,
];
$panel = $this->params['panel'];

if ($company_type == 'cus') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'sell')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'customer'), 'url' => ['index']];
    $this->title = Yii::t('backend/menu', 'customer');
} else if ($company_type == 'sup') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'buy')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'supplier'), 'url' => ['index']];
    $this->title = Yii::t('backend/menu', 'supplier');
} else if ($company_type == 'man') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'manufacture')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'injector'), 'url' => ['index']];
    $this->title = Yii::t('backend/menu', 'injector');
}
if (!empty($model->id)) {
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['item', 'id' => $model->id]];
    $this->title = $model->name;
}

$form = ActiveForm::begin([
            'id' => 'control-form',
        ]);
?>
<?= $form->errorSummary($model); ?>

<div class="row">
    <?= $form->field($model, 'code', ['options' => ['class' => 'col-xs-6 col-md-3']])->textInput(['maxlength' => true]) ?>
    <?=
    $form->field($model, 'org', ['options' => ['class' => 'col-xs-12 col-md-4']])->widget(Select2::classname(), [
        'data' => ItemAlias::getData('org'),
        'options' => ['multiple' => true],
        'disabled' => $panel['disabled'],
    ]);
    ?>
    <?=
    $form->field($model, 'type', ['options' => ['class' => 'col-xs-12 col-md-4']])->widget(Select2::classname(), [
        'data' => ItemAlias::getData('company_type'),
        'options' => ['multiple' => true],
        'disabled' => $panel['disabled'],
    ]);
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
    <?= $form->field($model, 'address', ['options' => ['class' => 'col-xs-12']])->textInput() ?>
    <?= $form->field($model, 'postcode', ['options' => ['class' => 'col-xs-6 col-md-3']])->textInput(['maxlength' => true, 'id' => 'ddl-postcode']) ?>
    <?=
    $form->field($model, 'province', ['options' => ['class' => 'col-xs-6 col-md-3']])->widget(DepDrop::classname(), [
        'options' => ['id' => 'ddl-province'],
        'data' => AreaController::getProvinces($model->postcode),
        'pluginOptions' => [
            'depends' => ['ddl-postcode'],
            'placeholder' => Yii::t('backend/general', 'select'),
            'url' => Url::to(['/area/postcode', 'area' => 'province'])
        ]
    ])
    ?>

    <?=
    $form->field($model, 'amphure', ['options' => ['class' => 'col-xs-6 col-md-3']])->widget(DepDrop::classname(), [
        'options' => ['id' => 'ddl-amphure'],
        'data' => AreaController::getAmphures($model->postcode, $model->province),
        'pluginOptions' => [
            'depends' => ['ddl-postcode', 'ddl-province'],
            'placeholder' => Yii::t('backend/general', 'select'),
            'url' => Url::to(['/area/postcode', 'area' => 'amphure'])
        ]
    ])
    ?>

    <?=
    $form->field($model, 'district', ['options' => ['class' => 'col-xs-6 col-md-3']])->widget(DepDrop::classname(), [
        'data' => AreaController::getDistricts($model->postcode, $model->amphure),
        'pluginOptions' => [
            'depends' => ['ddl-postcode', 'ddl-amphure'],
            'placeholder' => Yii::t('backend/general', 'select'),
            'url' => Url::to(['/area/postcode', 'area' => 'district'])
        ]
    ])
    ?>
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

<?php ActiveForm::end(); ?>