<?php
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\components\HeadNavigator;

$this->params['header'] = HeadNavigator::header();
$this->params['breadcrumbs'] = HeadNavigator::breadcrumbs($model->getFullName(false));

$this->params['panel'] = [
    'id' => 'tab',
    'tabs' => $tabs,
    'tabs_disabled' => false,
    'disabled' => false,
    'title' => $model->code . ' - ' . $model->getFullName(true),
    'controlbar' => [
        'button' => [
            'manage' => [
                'link' => ['files-update', 'id' => $model->id],
            ],
        ],
        'template_add' => ['manage'],
    ],
];

Pjax::begin();
$form = ActiveForm::begin();
?>

<div class="row">
    <?= $form->field($model, 'files', ['options' => ['class' => 'col-xs-12']])->filepanel(['class' => 'col-xs-6 col-sm-4 col-md-3 col-lg-2']) ?>
</div>

<?php
ActiveForm::end();
Pjax::end();
?>
