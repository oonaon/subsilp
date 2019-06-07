<?php

namespace common\components;

use yii\base\Widget;
use yii\bootstrap\Modal;

class ModalAjax extends Widget {

    public $id, $header='Modal Ajax', $size='modal-lg';

    public function run() {
        Modal::begin([
            'id' => $this->id,
            'header' => $this->header,
            'size' => $this->size,
            'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">ปิด</a>',
        ]);
        Modal::end();

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
