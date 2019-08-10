<?php

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\CompanyContact;
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
                'link' => ['location', 'id' => $model->company_id],
            ],
        ],
        'template_add' => ['save','cancel'],
    ],
];
Pjax::begin();
$form = ActiveForm::begin(['id' => 'control-form', 'enableClientValidation' => false, 'options' => ['enctype' => 'multipart/form-data']]);

if ($model->item_fix) {
    $disabled = true;
} else {
    $disabled = false;
}
?>
<?= $form->errorSummary($model); ?>

<div class="row">
    
    <?= $form->field($model, 'district', ['options' => ['class' => 'col-xs-12 col-md-12']])->selectAjax(['postcode'], 5, ['disabled' => $disabled])->label(Yii::t('common/model', 'postcode')) ?>
    
    <?= $form->field($model, 'address', ['options' => ['class' => 'col-xs-12']])->textarea(['rows' => 2, 'disabled' => $disabled]) ?>

    <?= $form->field($model, 'contact', ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput() ?>
    <?php
    $contacts = CompanyContact::findAll(['company_id' => $model->company_id]);
    $listData = ArrayHelper::map($contacts, 'id', 'name');
    ?>
    <?= $form->field($model, 'contact_id', ['options' => ['class' => 'col-xs-12 col-md-6']])->dropDownList($listData) ?>

    <?= $form->field($model, 'memo', ['options' => ['class' => 'col-xs-12']])->textarea(['rows' => 2]) ?>

    <?= $form->field($model, 'latitude', ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput() ?>
    <?= $form->field($model, 'longitude', ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput() ?>

    <?= $form->field($model, 'map', ['options' => ['class' => 'col-xs-12']])->file(['multiple' => true, 'accept' => 'image/*']) ?>

</div>

<?php
ActiveForm::end();
Pjax::end();
?>
