<?php

namespace common\components;

use common\models\ItemAlias;

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
        //$alias = itemAlias::getData('org');
        $out = '';
        foreach ($val as $item) {
            $letter= strtoupper(substr($item,0,1));
            if ($item == 'easy') {
                $out .= '<small class="label label-success">' . $letter . '</small> ';
            } else if ($item == 'con') {
                $out .= '<small class="label label-danger">' . $letter . '</small> ';
            }
        }

        return $out;
    }

}
