<?php
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\ItemAlias;

$disabled=$this->params['disabled'];
$company_type=$this->params['control']['classname'];

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
        'data' => itemAlias::getData('org'),
        'options' => ['multiple' => true],
        'disabled' => $disabled,
    ]);
    ?>
    <?=
    $form->field($model, 'type', ['options' => ['class' => 'col-xs-12 col-md-4']])->widget(Select2::classname(), [
        'data' => itemAlias::getData('company_type'),
        'options' => ['multiple' => true],
        'disabled' => $disabled,
    ]);
    ?>
</div>
<div class="row">
    <?= $form->field($model, 'kind', ['options' => ['class' => 'col-xs-6 col-md-3']])->dropDownList(itemAlias::getData('company_kind')) ?>
    <?= $form->field($model, 'name', ['options' => ['class' => 'col-xs-12 col-md-9']])->textInput(['maxlength' => true]) ?>
</div>

<div class="row">
    <?= $form->field($model, 'tax', ['options' => ['class' => 'col-xs-8 col-md-6']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'branch', ['options' => ['class' => 'col-xs-4 col-md-3']])->textInput() ?>
</div>

<div class="row">
    <?= $form->field($model, 'address', ['options' => ['class' => 'col-xs-12']])->textarea(['rows' => 3]) ?>
    <?= $form->field($model, 'subdistrict', ['options' => ['class' => 'col-xs-6']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'district', ['options' => ['class' => 'col-xs-6']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'province', ['options' => ['class' => 'col-xs-6']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'postcode', ['options' => ['class' => 'col-xs-6']])->textInput(['maxlength' => true]) ?>
</div>

<div class="row">
    <?= $form->field($model, 'tel', ['options' => ['class' => 'col-xs-6 col-md-3']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'fax', ['options' => ['class' => 'col-xs-6 col-md-3']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email', ['options' => ['class' => 'col-xs-6 col-md-3']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'website', ['options' => ['class' => 'col-xs-6 col-md-3']])->textInput(['maxlength' => true]) ?>
</div>

<div class="row">
    <?= $form->field($model, 'salesman', ['options' => ['class' => 'col-xs-6 col-md-3']])->textInput() ?>
    <?= $form->field($model, 'rank', ['options' => ['class' => 'col-xs-6 col-md-3']])->dropDownList(itemAlias::getData('company_rank')) ?>
    <?= $form->field($model, 'status', ['options' => ['class' => 'col-xs-6 col-md-3']])->dropDownList(itemAlias::getData('status')) ?>
</div>

<div class="row">
    <?= $form->field($model, 'payment', ['options' => ['class' => 'col-xs-12']])->textarea(['rows' => 3]) ?>
    <?= $form->field($model, 'credit', ['options' => ['class' => 'col-xs-12']])->textInput() ?>
</div>

<div class="row">
    <?= $form->field($model, 'transport', ['options' => ['class' => 'col-xs-12']])->dropDownList(itemAlias::getData('transport')) ?>
    <?= $form->field($model, 'transport_note', ['options' => ['class' => 'col-xs-12']])->textarea(['rows' => 3]) ?>
</div>

<div class="row">
    <?= $form->field($model, 'memo', ['options' => ['class' => 'col-xs-12']])->textarea(['rows' => 3]) ?>
</div>

<?php ActiveForm::end(); ?>