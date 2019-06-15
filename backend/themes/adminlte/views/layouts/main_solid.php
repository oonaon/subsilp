<?php $this->beginPage() ?>
<?= $this->render('begin') ?>
<div class="box box-solid">
    <div class="box-body">
        <?= $content; ?>
    </div>
</div>
<?= $this->render('end') ?>
<?php $this->endPage() ?>
