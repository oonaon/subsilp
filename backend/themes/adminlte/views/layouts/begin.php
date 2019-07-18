<?php

use dmstr\widgets\Alert;

if (class_exists('backend\assets\AppAsset')) {
    backend\assets\AppAsset::register($this);
} else {
    app\assets\AppAsset::register($this);
}
dmstr\web\AdminLteAsset::register($this);
$this->params['directory_asset'] = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

if(!empty($this->params['panel']['title'])){
    $this->title = $this->params['panel']['title'].' / '.$this->params['title'];
} else if(!empty($this->params['title'])) {
    $this->title = $this->params['title'];
}
?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <?= $this->render('head') ?>
    <body class="hold-transition <?= (Yii::$app->session['organize'] == 'con' ? 'skin-red' : 'skin-green') ?> sidebar-mini">
        <?php $this->beginBody() ?>
        <div class="wrapper">
            <?= $this->render('header') ?>
            <?= $this->render('menu') ?>
            <div class="content-wrapper">
                <?= $this->render('breadcrumb') ?>
                <section class="content">
                    <?= Alert::widget() ?>
                    <!--------- CONTENT --------->