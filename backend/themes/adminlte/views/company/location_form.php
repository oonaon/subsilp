<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\components\Area;
use common\models\CompanyContact;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use common\models\File;

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

    <?= $form->field($model_location, 'postcode', ['options' => ['class' => 'col-xs-6 col-md-4']])->textInput(['maxlength' => true, 'id' => 'ddl-postcode', 'disabled' => $disabled]) ?>

    <?= $form->field($model_location, 'district', ['options' => ['class' => 'col-xs-6 col-md-8']])->dropDownDependent('ddl-postcode', Area::getDistricts($model_location->postcode), ['id' => 'ddl-district', 'prompt' => Yii::t('backend/general', 'select'), 'disabled' => $disabled]) ?>

    <?= $form->field($model_location, 'address', ['options' => ['class' => 'col-xs-12']])->textarea(['rows' => 2, 'disabled' => $disabled]) ?>

    <?= $form->field($model_location, 'contact', ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput() ?>
    <?php
    $contacts = CompanyContact::findAll(['company_id' => $id]);
    $listData = ArrayHelper::map($contacts, 'id', 'name');
    ?>
    <?= $form->field($model_location, 'contact_id', ['options' => ['class' => 'col-xs-12 col-md-6']])->dropDownList($listData) ?>

    <?= $form->field($model_location, 'memo', ['options' => ['class' => 'col-xs-12']])->textarea(['rows' => 2]) ?>



</div>
<?=
$form->field($model_location, 'map[]')->widget(FileInput::classname(), [
    'options' => [
        //'accept' => 'image/*',
        'multiple' => true,
    ],
    'pluginOptions' => [
        'initialPreview' => File::initPreview($model_location->map),
        'initialPreviewConfig' => File::initPreview($model_location->map, 'config', ['deletefile']),
        //  'allowedFileExtensions'=>['pdf','jpg'],
        'showPreview' => true,
        'showCaption' => true,
        'showRemove' => true,
        'showUpload' => false,
        'overwriteInitial' => false,
        'theme' => 'explorer-fa',
    ],
    'pluginEvents' => [
        'filesorted' => 'function (event, params) {
        $.ajax({
                url: "http://xxxxxxxx",
                type: "POST",
                data: { stack: params.stack },
                success: function (data) {
                    alert("Success");
                },
                "error": function (request, status, error) {
                    alert("We are unable to set the sort order at this time.");
                }
            });
        }',
    ],
]);
?>

<?php
ActiveForm::end();
?>
