<?php

namespace common\components;

use common\models\ItemAlias;
use common\components\Area;

class CustomColumn {

    public function status($val) {
        $alias = itemAlias::getData('status');
        $out = '';
        if ($val == 1) {
            $out = '<small class="label label-success"><i class="fa fa-check"></i> ' . $alias[$val] . '</small>';
        } else {
            $out = '<small class="label label-danger"><i class="fa fa-times"></i> ' . $alias[$val] . '</small>';
        }
        return $out;
    }

    public function org($val) {
        if(!is_array($val)){
            $val= explode(',', $val);
        }
        $out = '';
        foreach ($val as $item) {
            $letter = strtoupper(substr($item, 0, 1));
            if ($item == 'easy') {
                $out .= '<small class="label label-success">' . $letter . '</small> ';
            } else if ($item == 'con') {
                $out .= '<small class="label label-danger">' . $letter . '</small> ';
            }
        }

        return $out;
    }

    public function item_label($model, $labels = []) {

        $out = '';

        if (!empty($labels)) {
            foreach ($labels as $label) {

                if ($label == 'default') {
                    $alias = itemAlias::getData('item_default');
                    $val=$model->item_default;
                    if ($val == 1) {
                        $out .= '<small class="label label-default">' . $alias[$val] . '</small> ';
                    } else {
                        $out .= '';
                    }
                } else if ($label == 'fix') {
                    $alias = itemAlias::getData('item_fix');
                    $val=$model->item_fix;
                    if ($val == 1) {
                        $out .= '<small class="label label-default">' . $alias[$val] . '</small> ';
                    } else {
                        $out .= '';
                    }
                }
            }
        }


        return $out;
    }

}
