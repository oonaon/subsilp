<?php

namespace common\components;

use Yii;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\Html;

class ModalAjax extends Widget {

    public $id, $header = 'Modal Ajax', $size = 'modal-lg';

    public function run() {
        Modal::begin([
            'id' => $this->id,
            'header' => $this->header,
            'size' => $this->size,
            'footer' => Html::button(Yii::t('backend/button', 'cancel'), ['class' => 'btn pull-left', 'data-dismiss' => 'modal']) . Html::submitButton(Yii::t('backend/button', 'save'), ['id' => 'btn-modal-submit', 'class' => 'btn pull-right']),
        ]);
        Modal::end();
        
        $this->getView()->registerJs('$("#btn-modal-submit").click(function(){ 
                    $("#form-modal").submit();
                });', \yii\web\View::POS_READY);
        
        $this->getView()->registerJs('
            $("a[data-target=\'#' . $this->id . '\']").click(function(e) {
                var url = $(this).attr("href"), idx = url.indexOf("#")
                var hash = idx != -1 ? url.substring(idx+1) : "";
                if(hash){
                    class_size = hash;
                } else {
                    class_size = "' . $this->size . '";
                }
                $("#' . $this->id . '").find(".modal-dialog").attr("class", "modal-dialog "+class_size);
                $("#' . $this->id . '").find(".modal-body").html("<div class=\"overlay\"><i class=\"fa fa-refresh fa-spin\"></i></div>");
                $("#' . $this->id . '").find(".modal-body").load(url);
                $("#' . $this->id . '").modal("show");
                return false;
            });
        ');     
        
        return false;
    }

}
