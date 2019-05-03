<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
<div class="box">
    <div class="box-body" style="padding-bottom: 0px;">

        <?php
        if (is_array($buttons)) {
            foreach ($buttons as $button) {
                echo '<div class="btn-group">';
                if (is_array($button)) {
                    foreach ($button as $btn) {

                        $options = [];
                        $options['class'] = 'btn btn-app';
                        if ($btn['disabled']) {
                            $options['class'] .= ' disabled';
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
        ?>

    </div>
</div>

<div class="modal fade" id="modal-search" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php $form = ActiveForm::begin(['action' => ['find']]); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= Yii::t('backend/header', 'search_code') ?></h4>
            </div>

            <div class="modal-body">
                <div class="input-group">
                    <span class="input-group-addon"><b>C</b></span>
                    <?= Html::textInput('find_code', '', ['class' => 'form-control']) ?>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?= Yii::t('backend/button', 'close') ?></button>
                <?= Html::submitButton(Yii::t('backend/button', 'ok'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

