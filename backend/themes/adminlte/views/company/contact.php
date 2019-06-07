<?php

use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Html;

$disabled = $this->params['disabled'];
$company_type = $this->params['control']['classname'];

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


Pjax::begin(['id'=>'pjax_panel']);
?>


          


<?php
$form = ActiveForm::begin([
            'id' => 'model-form',
        ]);
?>
<?= $form->errorSummary($model_contact); ?>

<div class="box-header with-border">
    <h3 class="box-title">เพิ่มข้อมูลใหม่</h3>
</div> 



<div class="box-body">
    <div class="row">
        <?= $form->field($model_contact, 'name', ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['maxlength' => true]) ?>
        <?= $form->field($model_contact, 'contact', ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['maxlength' => true]) ?>
    </div>
</div>


<div class="box-footer">
    <button type="submit" class="btn btn-default">Cancel</button>
    <?= Html::submitButton(Yii::t('backend/button', 'save'), ['class' => 'btn pull-right']) ?>
</div>



<?php
ActiveForm::end();
?>



<div class="box-header with-border">
    <h3 class="box-title">เพิ่มข้อมูลใหม่</h3>
</div> 

<?php
if ($disabled) {
    $action_btn = [];
} else {
    $action_btn = [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{update} {delete}',
        'urlCreator' => function ($button, $item, $key, $index) use ($model) {
            if ($button === 'update') {
                $url = ['contact', 'id' => $model->id, 'mode' => 'update', 'sid' => $item->id];
                return $url;
            }
            if ($button === 'delete') {
                $url = ['contact', 'id' => $model->id, 'mode' => 'delete', 'sid' => $item->id];
                return $url;
            }
        },
    ];
}
echo GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => 'yii\grid\RadioButtonColumn',
            'radioOptions' => function ($model) {
                return [
                    'value' => $model['id'],
                    'checked' => $model['id'] == 1
                ];
            }
        ],
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        'contact',
        'memo',
        $action_btn,
    ],
]);

Pjax::end();
?>
    
