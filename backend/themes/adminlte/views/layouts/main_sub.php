<?php

use common\components\ControlBar;
use common\components\ModalAjax;
use common\components\Button;
use yii\helpers\Url;
use yii\helpers\Html;

$panel = $this->params['panel'];
$panel['controlbar'] = (empty($panel['controlbar'])) ? '' : $panel['controlbar'];
?>
<?php $this->beginPage() ?>
<?= $this->render('begin') ?>

<div class="page-layout">
    <div class="row">
        <div class="col-sm-12 col-md-3">
            <div class="box box-solid">
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <?php
                        if (!empty($panel['category'])) {
                            foreach ($panel['category'] as $category) {
                                if (!empty($category['active'])) {
                                    $active = 'class="active"';
                                } else {
                                    $active = '';
                                }
                                echo '<li ' . $active . '>';
                                echo Html::a(Yii::t('common/itemalias', $category['label']), $category['link']);
                                echo '</li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>   
        </div>

        <div class="col-sm-12 col-md-9">
            <div class="box">
                <div class="box-header">
                    <?= ControlBar::widget(['params' => $panel['controlbar']]) ?> 
                </div>
                <div class="box-body">
                    <?= $content; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->render('end') ?>
<?php $this->endPage() ?>
