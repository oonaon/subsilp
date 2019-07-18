<?php

use yii\helpers\Url;
use common\components\ControlBar;
use common\components\ModalAjax;
use common\components\Button;

?>
<?php $this->beginPage() ?>
<?= $this->render('begin') ?>

<div class="page-layout">



    <div class="box">
        <div class="box-header">

            <nav class="navbar navbar-default" style="margin-bottom: 10px;">
                <div class="navbar-header">
                    <span class="navbar-brand">รายการ</span>
                </div>
            </nav>

            <?= ControlBar::widget() ?>  

        </div>
        <div class="box-body">
            <?= $content; ?>
        </div>
    </div>
</div>



<?= $this->render('end') ?>
<?php $this->endPage() ?>
