<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;


/* @var $this yii\web\View */
/* @var $model common\models\Company */
/* @var $form yii\widgets\ActiveForm */
$icons = [
    'index' => '<i class="fa fa-list"></i>',
    'add' => '<i class="fa fa-plus"></i>',
    'update' => '<i class="fa fa-edit"></i>',
    'save' => '<i class="fa fa-save"></i>',
    'cancel' => '<i class="fa fa-ban"></i>',
    'delete' => '<i class="fa fa-trash"></i>',
    'search' => '<i class="fa fa-search"></i>',
    'first' => '<i class="fa fa-angle-double-left"></i>',
    'previous' => '<i class="fa fa-angle-left"></i>',
    'next' => '<i class="fa fa-angle-right"></i>',
    'last' => '<i class="fa fa-angle-double-right"></i>',
];
?>

<?php
if (is_array($buttons)) {
    foreach ($buttons as $button) {
        echo '<div class="btn-group">';
        if (is_array($button)) {
            foreach ($button as $btn) {

                $options = [];
                $options['class'] = 'btn btn-app';

                if ($btn['disabled']) {
                    $options['class'] .= ' disabled hidden-xs';
                } else {
                    if ($btn['name'] == 'save') {
                        $options['class'] .= ' text-green';
                    } else if ($btn['name'] == 'cancel') {
                        $options['class'] .= ' text-red';
                    }
                }
                if ($btn['modal']) {
                    $options['data-toggle'] = 'modal';
                    $options['data-target'] = '#modal-' . $btn['modal'];
                }

                if ($btn['name'] == 'delete') {
                    $options['data'] = [
                        'confirm' => Yii::t('backend/general', 'confirm_delete'),
                        'method' => 'post',
                    ];
                } else if ($btn['name'] == 'save') {
                    $options['id'] = 'btn-save';
                }

                echo Html::a($icons[$btn['name']] . ' ' . Yii::t('backend/button', $btn['name']), $btn['link'], $options);
            }
        }
        echo '</div> ';
    }
}

$this->registerJs(' 
        $("#btn-save").click(function () {
            $("#control-form").submit();
        });', \yii\web\View::POS_READY);


// ***** START MODEL SEARCH *****
Modal::begin([
    'header' => Yii::t('backend/header', 'search_code'),
    'id' => 'modal-search',
    'size' => 'modal-md',
    'footer' => '<button type="button" class="btn btn-default pull-left" data-dismiss="modal">' . Yii::t('backend/button', 'close') . '</button>' . Html::submitButton(Yii::t('backend/button', 'ok'), ['id' => 'btn-model-search', 'class' => 'btn btn-primary']),
]);
$form = ActiveForm::begin([
            'id' => 'form-search-model',
            'action' => ['find'],
        ]);

echo Html::textInput('find_code', '', ['class' => 'form-control']);
$this->registerJs('$("#btn-model-search").click(function(){$("#form-search-model").submit();});', \yii\web\View::POS_READY);
ActiveForm::end();
Modal::end();
// ***** END MODEL SEARCH *****

?>