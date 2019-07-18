<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\components\Area;
use common\models\CompanyContact;
use yii\widgets\ActiveForm;


$form = ActiveForm::begin([
            'id' => 'form-modal',
            'options' => ['enctype' => 'multipart/form-data'],
        ]);

?>
<?= $form->errorSummary($model); ?>


<div class="row">
    <?= $form->field($model, 'files', ['options' => ['class' => 'col-xs-12']])->file(['multiple' => true, 'accept' => 'image/*']) ?>
</div>

<?php
ActiveForm::end();
?>
