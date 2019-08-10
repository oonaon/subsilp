<?php

namespace common\components;

use Yii;
use yii\widgets\ActiveForm;

class CActiveForm extends ActiveForm {

    public $fieldClass = 'common\components\CActiveField';

    public function setupScript() {
        $this->getView()->registerJs(' 
            
            $("#btn-save").click(submitForm);
            $("[data-pjax-update]").change(submitPjax);
            
            function submitPjax(){
                $("#' . $this->id . '").attr("data-pjax",true);
                $("#' . $this->id . '").submit();
            }
            function submitForm(){
                $("#' . $this->id . '").removeAttr("data-pjax");
                $("#' . $this->id . '").submit();
            }
        ', \yii\web\View::POS_READY);
    }

}

?>