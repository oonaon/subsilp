<?php

namespace common\components;

use Yii;
use yii\helpers\Html;

class Button {

    public static function a($name, $link, $options=[], $show = '') {
        if (empty($options['class'])) {
            $options['class'] = 'btn btn-default btn-sm';
        }
        if ($show == 'icon') {
            return Html::a(self::icon($name), $link, $options);
        } else if ($show == 'text') {
            return Html::a(Yii::t('backend/button', $name), $link, $options);
        } else {
            return Html::a(self::icon($name) . ' ' . Yii::t('backend/button', $name), $link, $options);
        }
    }
    
    public static function icon($name) {
        $icon = [
            'index' => '<i class="fa fa-list"></i>',
            'search' => '<i class="fa fa-search"></i>',
            'add' => '<i class="fa fa-plus"></i>',
            'update' => '<i class="fa fa-edit"></i>',
            'add_update' => '<i class="fa fa-edit"></i>',
            'view' => '<i class="fa fa-newspaper-o"></i>',
            'save' => '<i class="fa fa-save"></i>',
            'cancel' => '<i class="fa fa-ban"></i>',
            'delete' => '<i class="fa fa-trash"></i>',
            'properties' => '<i class="fa fa-pencil-square-o"></i>',
            'images' => '<i class="fa fa-image"></i>',
            'stocks' => '<i class="fa fa-bank"></i>',
            'prices' => '<i class="fa fa-money"></i>',
            'contact' => '<i class="fa fa-mobile"></i>',
            'location' => '<i class="fa fa-location-arrow"></i>',
            'files' => '<i class="fa fa-file"></i>',
            
            'first' => '<i class="fa fa-angle-double-left"></i>',
            'previous' => '<i class="fa fa-angle-left"></i>',
            'next' => '<i class="fa fa-angle-right"></i>',
            'last' => '<i class="fa fa-angle-double-right"></i>',
        ];
        if (empty($icon[$name])) {
            return '';
        } else {
            return $icon[$name];
        }
    }

}
