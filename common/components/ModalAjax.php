<?php

namespace common\components;

use Yii;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\Html;

class ModalAjax extends Widget {

    public $id, $header='Modal Ajax', $size='modal-lg';

    public function run() {
        Modal::begin([
            'id' => $this->id,
            'header' => $this->header,
            'size' => $this->size,
            'footer' => Html::button(Yii::t('backend/button', 'cancel'), ['class' => 'btn pull-left','data-dismiss'=>'modal']).Html::submitButton(Yii::t('backend/button', 'save'), ['id'=>'btn-modal-submit', 'class' => 'btn pull-right']),
        ]);
        Modal::end();
        
        $this->getView()->registerJs('$("#btn-modal-submit").click(function(){$("#form-modal").submit();});', \yii\web\View::POS_READY);
        $this->getView()->registerJs('
            $("a[data-target=\'#'.$this->id.'\']").click(function(e) {
                $("#'.$this->id.'").find(".modal-body").html("<i class=\"fa fa-refresh fa-spin\"></i>");
                $.get($(this).attr("href"),function (data){
                    $("#'.$this->id.'").find(".modal-body").html(data);
                    $("#'.$this->id.'").modal("show");
                });
                return false;
            });
        ');

        return false;
    }

}
