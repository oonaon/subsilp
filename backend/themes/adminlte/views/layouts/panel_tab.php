<?php

use yii\helpers\Url;
use common\components\ControlBar;
use common\components\ModalAjax;

$panel = $this->params['panel'];
?>
<div class="page-layout">

    <?= ControlBar::widget($this->params['controlbar']) ?>  

    <div class="page-form">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <?php
                foreach ($panel['tabs'] as $tab) {
                    $active = false;
                    if (in_array(Yii::$app->controller->action->id, [$tab, $tab . '_create', $tab . '_update'])) {
                        $active = true;
                    }


                    echo '<li class="';
                    if ($active) {
                        echo 'active ';
                    }
                    if ($panel['tabs_disabled']) {
                        echo 'disabled ';
                    }
                    echo '"><a ';
                    if (!$active && !$panel['tabs_disabled']) {
                        echo 'href="' . Url::current([$tab]) . '"';
                    }
                    echo '>' . Yii::t('backend/tab', $tab) . '</a></li>';
                }
                if (!empty($panel['tools'])) {
                    echo '<li class="pull-right">' . $panel['tools'] . '</li>';
                }
                ?>

            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <fieldset <?= ($panel['disabled'] ? 'disabled="disabled"' : '') ?>>
<?= $content; ?>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>

</div>
<?= ModalAjax::widget(['id' => 'modal-ajax', 'size' => 'modal-lg']) ?>