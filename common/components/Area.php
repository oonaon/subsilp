<?php

namespace common\components;

use Yii;
use yii\base\Model;
use common\models\AreaDistricts;

class Area {

    public function getDistricts($postcode) {
        $areas = self::getAreaFromPostcode($postcode);
        $data = [];
        if (!empty($areas)) {
            foreach ($areas as $area) {
                $data[$area['district']['id']] = $area['district']['name'] . ' / ' . $area['amphure']['name'] . ' / ' . $area['province']['name'];
            }
        }
        return $data;
    }

    public function getAreaFromPostcode($postcode, $filter = '') {
        if (!empty($postcode) && is_numeric($postcode) && strlen($postcode) == 5) {
            $districts = AreaDistricts::find()->where(['postcode' => $postcode])->orderBy('id asc')->all();
            if (!empty($districts)) {
                $out = [];
                foreach ($districts as $district) {
                    $arr = [];
                    $add = true;
                    $amphure = $district->amphure;
                    $province = $amphure->province;
                    $geography = $province->geography;

                    if ($add && (!empty($filter['province'])) && ($province->id != $filter['province'])) {
                        $add = false;
                    }
                    if ($add && (!empty($filter['amphure'])) && ($amphure->id != $filter['amphure'])) {
                        $add = false;
                    }
                    if ($add) {
                        $arr['postcode'] = $district->postcode;
                        $arr['district'] = ['id' => $district->id, 'name' => $district->name_th];
                        $arr['amphure'] = ['id' => $amphure->id, 'name' => $amphure->name_th];
                        $arr['province'] = ['id' => $province->id, 'name' => $province->name_th];
                        $arr['geography'] = ['id' => $geography->id, 'name' => $geography->name_th];
                        $out[] = $arr;
                    }
                }
                return $out;
            }
        }
        return false;
    }

    public function getAreaFromDistrict($district_id) {
        if (!empty($district_id)) {
            $district = AreaDistricts::findOne($district_id);
            if (!empty($district)) {
                $arr = [];
                $amphure = $district->amphure;
                $province = $amphure->province;
                $geography = $province->geography;
                $arr['postcode'] = $district->postcode;
                $arr['district'] = ['id' => $district->id, 'name' => $district->name_th];
                $arr['amphure'] = ['id' => $amphure->id, 'name' => $amphure->name_th];
                $arr['province'] = ['id' => $province->id, 'name' => $province->name_th];
                $arr['geography'] = ['id' => $geography->id, 'name' => $geography->name_th];
                return $arr;
            }
        }
        return false;
    }
    
    public function fullAddress($model) {
        if (!empty($model->district)) {
            $area = self::getAreaFromDistrict($model->district);
            return $model->address.' '.$area['district']['name'].' '.$area['amphure']['name'].' '.$area['province']['name'].' '.$model->postcode;  
        }
        return false;
    }

}
