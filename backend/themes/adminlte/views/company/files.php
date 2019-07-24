<?php
use yii\helpers\Url;
use common\models\ItemAlias;
use kartik\select2\Select2;
use common\components\Area;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\File;

$controller=Yii::$app->controller->id;
if ($controller == 'customer') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'sell')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'customer'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['item', 'id' => $model->id]];
    $this->params['title'] = Yii::t('backend/menu', 'customer');
} else if ($controller == 'supplier') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'buy')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'supplier'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['item', 'id' => $model->id]];
    $this->params['title'] = Yii::t('backend/menu', 'supplier');
} else if ($controller == 'manufacturer') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'manufacture')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('backend/menu', 'injector'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['item', 'id' => $model->id]];
    $this->params['title'] = Yii::t('backend/menu', 'injector');
}

$this->params['panel'] = [
    'id' => 'tab',
    'tabs' => $tabs,
    'tabs_disabled' => false,
    'disabled' => false,
    'title' => $model->name,
    'controlbar' => [
        'button' => [
            'add_update' => [
                'link' => ['files_update', 'id' => $model->id, '#' => 'modal-md'],
                'modal' => 'modal-ajax',
            ],
        ],
        'template_add' => ['add_update'],
    ],
];

$form = ActiveForm::begin();
?>

<div class="row">
    <?= $form->field($model, 'files', ['options' => ['class' => 'col-xs-12']])->filepanel(['class' => 'col-xs-6 col-sm-4 col-md-3 col-lg-2']) ?>
</div>

<?php
ActiveForm::end();
?>
