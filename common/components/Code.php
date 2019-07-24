<?php

namespace common\components;

use Yii;
use yii\helpers\Html;

class Code {

    public static function frontBill($year = '', $month = '') {
        $prefix=self::prefixCode();
        if (!empty($year) && !empty($month)) {
            if ($year > 99) {
                $year = substr($year, 2, 2);
            }
        } else {
            $year = date('y');
            $month = date('m');
        }
        return $prefix . str_pad($year + 43, 2, '0', STR_PAD_LEFT) . str_pad($month, 2, '0', STR_PAD_LEFT);
    }

    public static function generateBillCode($code = '') {
        $prefix=self::prefixCode();
        if (!empty($code)) {
            $text = str_replace($prefix, '', $code);
            $num = (int) substr($text, 4, 3);
            $num++;
        } else {
            $num = 1;
        }      
        $code = self::frontBill($prefix) . str_pad($num, 3, '0', STR_PAD_LEFT);
        return $code;
    }

    public static function frontCompany() {        
        return self::prefixCode();
    }

    public static function generateCompanyCode($code = '') {
        $prefix=self::prefixCode();
        if (!empty($code)) {
            $num = (int) str_replace(self::frontCompany($prefix), '', $code);
            $num++;
        } else {
            $num = 1;
        }
        $code = self::frontCompany($prefix) . $num;
        return $code;
    }
    
    public static function prefixCode() {
        $controller = Yii::$app->controller->id;
        $class = Yii::$app->params['class_model'][$controller];
        $org_prefix = strtoupper(substr(Yii::$app->session['organize'], 0, 1));
        
        if($controller=='customer' || $controller=='supplier' || $controller=='manufacturer'){
            return $org_prefix.$class['primary_prefix'];
        }
        return $class['primary_prefix'];
    }

}
