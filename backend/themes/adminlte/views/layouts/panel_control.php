<?php
use yii\helpers\Url;
use common\components\ControlBar;
use common\components\ModalAjax;

$tabs=$this->params['tabs'];
$mode=$this->params['control']['mode'];
$disabled=$this->params['disabled'];
?>
<div class="page-layout">

    <?= ControlBar::widget($this->params['control']) ?>  

    <div class="page-form">
        <fieldset <?= ($disabled ? 'disabled="disabled"' : '') ?>>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <?php
                    foreach ($tabs as $tab) {
                        echo '<li class="';
                        if ($tab == Yii::$app->controller->action->id) {
                            echo 'active ';
                        }
                        if (!$disabled) {
                            echo 'disabled ';
                        }
                        echo '"><a ';
                        if ($disabled) {
                            echo 'href="' . Url::current([$tab]) . '"';
                        }
                        echo '>' . Yii::t('backend/tab', $tab) . '</a></li>';
                    }
                    ?>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">
                        <?= $content; ?>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>

</div>
<?=ModalAjax::widget(['id' => 'modal-ajax','size'=>'modal-lg']) ?>