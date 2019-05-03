<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use common\models\MessageSource;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\MessageSource */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-source-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category')->widget(AutoComplete::className(), [
      'options' => [
      'class' => 'form-control'
      ],
      'clientOptions' => [
      'source' => MessageSource::find()
                ->groupBy(['category'])
                ->select(['category as value', 'category as label', 'id as id'])
                ->asArray()
                ->all(),
      'autoFill' => true,
      ],
      ]); 
    ?>

    <?php
    /*
    echo $form->field($model, 'category')->widget(Select2::classname(), [
        'pluginOptions' => [
            'allowClear' => true,
            'ajax' => [
                'url' => Url::to(['ajax/languagecategory']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {input:params.term};}')
            ],
        ],
    ]);
     */
    ?>

    <?php // $form->field($model, 'category')->textInput(['maxlength' => true])   ?>

    <?= $form->field($model, 'message')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lang_th')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'lang_en')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
