<?php

use yii\helpers\Url;
use common\models\ItemAlias;
use kartik\select2\Select2;
use common\components\Area;
use common\components\Button;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\File;

$this->params['panel'] = [
    'tabs' => $tabs,
    'tabs_disabled' => false,
    'disabled' => false,
    'title' => $model->code,
    'controlbar' => [
        'button' => [
            'add_update' => [
                'link' => ['images_update', 'id' => $model->id, '#' => 'modal-md'],
                'modal' => 'modal-ajax',
            ],
        ],
        'template_add' => ['add_update'],
    ],
];

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'manufacture')];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'injector'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'id' => $model->id]];
$this->params['title'] = Yii::t('backend/menu', 'product');


$form = ActiveForm::begin();
?>

<div class="row">
    <?= $form->field($model, 'images', ['options' => ['class' => 'col-xs-12']])->filepanel(['class' => 'col-xs-6 col-sm-4 col-md-3 col-lg-2']) ?>
</div>

<?php
ActiveForm::end();
?>
