<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MessageSource */

$this->title = Yii::t('app', 'Create Message Source');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Message Sources'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="message-source-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
