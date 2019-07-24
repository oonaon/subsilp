<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\components\Area;
use common\models\CompanyContact;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

Pjax::begin();
$form = ActiveForm::begin([
            'id' => 'form-modal',
            'options' => ['enctype' => 'multipart/form-data'],
        ]);
if ($model_location->item_fix) {
    $disabled = true;
} else {
    $disabled = false;
}
?>
<?= $form->errorSummary($model_location); ?>

<div class="row">
    
    <?= $form->field($model_location, 'district', ['options' => ['class' => 'col-xs-12 col-md-12']])->selectAjax(['postcode'], 5, ['disabled' => $disabled])->label(Yii::t('common/model', 'postcode')) ?>
    
    <?= $form->field($model_location, 'address', ['options' => ['class' => 'col-xs-12']])->textarea(['rows' => 2, 'disabled' => $disabled]) ?>

    <?= $form->field($model_location, 'contact', ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput() ?>
    <?php
    $contacts = CompanyContact::findAll(['company_id' => $id]);
    $listData = ArrayHelper::map($contacts, 'id', 'name');
    ?>
    <?= $form->field($model_location, 'contact_id', ['options' => ['class' => 'col-xs-12 col-md-6']])->dropDownList($listData) ?>

    <?= $form->field($model_location, 'memo', ['options' => ['class' => 'col-xs-12']])->textarea(['rows' => 2]) ?>

    <?= $form->field($model_location, 'latitude', ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput() ?>
    <?= $form->field($model_location, 'longitude', ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput() ?>

    <?= $form->field($model_location, 'map', ['options' => ['class' => 'col-xs-12']])->file(['multiple' => true, 'accept' => 'image/*']) ?>

</div>

<?php
ActiveForm::end();
Pjax::end();
?>
