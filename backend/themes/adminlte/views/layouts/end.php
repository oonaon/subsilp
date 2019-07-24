<?php
use common\components\ModalAjax;
?>
                <!------- END CONTENT ------->
                </section>
            </div>
            <?= $this->render('footer') ?>
            <?= $this->render('sidebar') ?>
            <?= $this->render('widget') ?>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?= ModalAjax::widget(['id' => 'modal-ajax']) ?>