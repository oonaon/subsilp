<?php

namespace common\components;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveField;
use kartik\sortinput\SortableInput;
use common\models\File;

//use yii\widgets\ActiveForm;

class CActiveField extends ActiveField {

    public function dropDownDependent($dependent, $items, $url, $options = []) {
        $options['prompt'] = empty($options['prompt']) ? 'Select..' : $options['prompt'];
        $options['id'] = empty($options['id']) ? 'ddl-area' : $options['id'];
        $id = $options['id'];
        $this->form->getView()->registerJs('
        dependent=$("#' . $dependent . '");
        dependent.change(function() {
            var href = "' . URL::to($url) . '";
            var url=href.replace("%7Bval%7D", dependent.val());
            $.post( url, function( data ) {
                $("#' . $id . '").empty();
                $("#' . $id . '").append("<option>' . $options['prompt'] . '</option>");
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

    public function file($options = []) {
        $model = $this->model;
        $attribute = $this->attribute;
        $data = $model[$attribute];
        $items = [];
        if (!empty($data)) {
            $data = explode(',', $data);
            foreach ($data as $id) {
                $f = File::findFile($id);

                $content = '<div class="row">';

                $content .= '<div class="col-xs-2">';
                $content .= File::icon($f->url, $f->url, $f->caption, 'file_preview', ['class' => 'img-thumbnail float-left']);
                $content .= '</div>';

                $content .= '<div class="col-xs-10">';
                $content .= Html::input('text', $attribute . '_file_caption[' . $id . ']', $f->caption, ['id' => $attribute . '_file_caption_id_' . $id, 'class' => 'form-control', 'disabled' => true]);

                $content .= '<div class="btn-group pull-right">
                                <a id="file_download_' . $id . '" href="' . $f->url . '" class="btn btn-default" download><i class="fa fa-download"></i></a>
                                <button id="file_edit_' . $id . '" type="button" class="btn btn-default" data-enabled="' . $id . '"><i class="fa fa-pencil"></i></button>  
                                <button id="file_move_' . $id . '" type="button" class="btn btn-default handle" draggable="true" style="display: none;"><i class="fa fa-arrows"></i></button> 
                                <button id="file_delete_' . $id . '" type="button" class="btn btn-default" data-widget="remove" style="display: none;"><i class="fa fa-trash"></i></button> 
                            </div>';


                //$content .= '<b>' . $f->filename . '</b>';
                $content .= '<span class="text-sm pull-left">' . strtoupper($f->extension) . ' / ' . File::format($f->size) . '</span> ';

                $content .= '</div>';

                $content .= '</div>';

                $items[$id] = ['content' => $content];
            }
            $input_id = self::getID($options);
            $this->form->getView()->registerJs('
                $("#' . $input_id . '-sortable button[data-enabled]").click(function(e) {
                    file_id=$(this).data("enabled");
                    $("#' . $attribute . '_file_caption_id_"+file_id).prop( "disabled", false );
                    $("#file_move_"+file_id).prop( "style", false );                    
                    $("#file_delete_"+file_id).prop( "style", false );
                    $(this).prop( "style", "display: none;" );
                });
                
                $("#' . $input_id . '-sortable button[data-widget=\"remove\"]").click(function(e) {
                    $(this).closest("li").remove();   
                    var items = [];
                    $("#' . $input_id . '-sortable li").each(function(){
                        items.push($(this).attr("data-key"));
                    });
                    if(items.length==0){
                        $("#' . $input_id . '-sortable").append("<li>' . Yii::t('backend/general', 'not_found') . '</li>");
                        $("#' . $input_id . '").val("");
                    } else {
                        $("#' . $input_id . '").val(items.join(","));
                    }
                });
            ', \yii\web\View::POS_READY);
        }
        if (empty($items)) {
            $items[] = ['content' => Yii::t('backend/general', 'not_found')];
        }
        echo $this->widget(SortableInput::classname(), [
            'items' => $items,
            'hideInput' => true,
            'sortableOptions' => [
                'showHandle' => true,
                'handleLabel' => false,
            ],
        ]);
        $this->attribute = $this->attribute . "_upload[]";
        $label = $model->attributeLabels();
        return $this->fileInput($options)->label(Yii::t('common/model', 'upload') . $label[$attribute]);
    }

    private function getID($options) {
        if (!empty($options['id'])) {
            return $options['id'];
        } else {
            return Html::getInputId($this->model, $this->attribute);
        }
    }

}

?>