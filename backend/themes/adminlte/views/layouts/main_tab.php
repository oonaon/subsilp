<?php

use yii\helpers\Url;
use common\components\ControlBar;
use common\components\Button;


$panel = $this->params['panel'];
$panel['controlbar'] = (empty($panel['controlbar'])) ? '' : $panel['controlbar'];
?>
<?php $this->beginPage() ?>
<?= $this->render('begin') ?>
<div class="page-layout">


    <div class="box">

        <div class="box-header">  
            <nav class="navbar navbar-default" style="margin-bottom: 10px;">
                <div class="navbar-header">
                    <?php
                    if (count($panel['tabs']) > 0) {
                        ?>
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#panel_nav" style="color: #444;">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>
                        <?php
                    }
                    ?>
                    <span class="navbar-brand"><?= $panel['title'] ?></span>   
                </div>

                <div class="collapse navbar-collapse" id="panel_nav">
                    <ul class="nav navbar-nav navbar-right">
                        <?php
                        foreach ($panel['tabs'] as $tab) {
                            $active = false;
                            $action=explode('-',Yii::$app->controller->action->id);
                            $action=$action[0];
                            if ($action == $tab) {
                                $active = true;
                            } else if ($action == 'update' && $tab == 'view') {
                                $active = true;
                            }
                            echo '<li class="';
                            if ($active) {
                                echo 'active ';
                            } else if ($panel['tabs_disabled']) {
                                echo 'disabled ';
                            }
                            echo '"><a ';
                            if (!$active && !$panel['tabs_disabled']) {
                                echo 'href="' . Url::current([$tab]) . '"';
                            }
                            echo '>' . Button::icon($tab) . ' ' . Yii::t('backend/tab', $tab) . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>

            </nav>

            <?= ControlBar::widget(['params' => $panel['controlbar']]) ?> 

            <!--
            <div class="pull-right">
                <ul class="pagination pagination-sm no-margin pull-right">
                    <li><a href="#"><i class="fa fa-chevron-left"></i></a></li>
                    <li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
                </ul>
            </div>
            -->

        </div>
        <div class="box-body">
            
            <fieldset <?= ($panel['disabled'] ? 'disabled="disabled"' : '') ?>>
                <?= $content; ?>
            </fieldset>
            
        </div>
    </div>

</div>
<?= $this->render('end') ?>
<?php $this->endPage() ?>
