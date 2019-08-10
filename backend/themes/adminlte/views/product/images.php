<?php
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\components\HeadNavigator;

$this->params['header'] = HeadNavigator::header();
$this->params['breadcrumbs'] = HeadNavigator::breadcrumbs($model->code);

$this->params['panel'] = [
    'tabs' => $tabs,
    'tabs_disabled' => false,
    'disabled' => false,
    'title' => $model->code,
    'controlbar' => [
        'button' => [
            'manage' => [
                'link' => ['images-update', 'id' => $model->id],
            ],
        ],
        'template_add' => ['manage'],
    ],
];

Pjax::begin();
$form = ActiveForm::begin();
?>

<div class="row">
    <?= $form->field($model, 'images', ['options' => ['class' => 'col-xs-12']])->filepanel(['class' => 'col-xs-6 col-sm-4 col-md-3 col-lg-2']) ?>
</div>

<?php
ActiveForm::end();
Pjax::end();
?>
