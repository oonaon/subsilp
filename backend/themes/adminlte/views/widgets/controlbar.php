<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use common\components\Button;

if (is_array($buttons)) {
    $i=0;
    foreach ($buttons as $position => $button) {
        
        if($i>0){
            $style='style="padding-left:10px;"';
        } else {
            $style='';
        }
        if ($position=='right') {
            echo '<div class="btn-group pull-right">';
        } else {
            echo '<div class="btn-group" '.$style.'>';
            $i++;
        }
        foreach ($button as $btn) {
            $options = [];
                $options['class'] = 'btn btn-default btn-sm';
                $options['style'] = 'margin-bottom:0px;';

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
                    $options['data-target'] = '#' . $btn['modal'];
                }

                if ($btn['name'] == 'delete') {
                    $options['data'] = [
                        'confirm' => Yii::t('backend/general', 'confirm_delete'),
                        'method' => 'post',
                    ];
                } else if ($btn['name'] == 'save') {
                    $options['id'] = 'btn-save';
                }
                echo Button::a($btn['name'], $btn['link'], $options,$btn['button']);

        }
        echo '</div>';
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