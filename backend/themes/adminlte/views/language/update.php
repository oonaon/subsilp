<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MessageSource */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Message Sources'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
$this->params['title'] = Yii::t('backend/menu', 'language');
?>
<div class="message-source-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
