<?php

namespace common\components;

use yii\widgets\ActiveField;

class CActiveField extends ActiveField {

    public function dropDownDependent($dependent, $items, $options = []) {
        $options['prompt'] = empty($options['prompt']) ? 'Select..' : $options['prompt'];
        $options['id'] = empty($options['id']) ? 'ddl-area' : $options['id'];
        $id = $options['id'];
        $this->form->getView()->registerJs('
        dependent=$("#' . $dependent . '");
        dependent.change(function() {
            url="/index.php?r=area/postcode&postcode="+dependent.val();
            $.post( url, function( data ) {
                $("#' . $id . '").empty();
                $("#' . $id . '").append("<option>'.$options['prompt'].'</option>");
                $.each(data["output"], function(key, item) { 
                    $selected="";
                    if(data["selected"]==item["id"]){
                        $selected="selected";
                    }
                    $("#' . $id . '").append("<option value=\'"+item["id"]+"\' "+$selected+">"+item["name"]+"</option>");
                });
            });
        });', \yii\web\View::POS_READY);
        return $this->dropDownList($items, $options);
    }

}
?>