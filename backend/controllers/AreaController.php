<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\AreaDistricts;

class AreaController extends Controller {

    public function behaviors() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
        ];
    }

    public function actionTest() {
        $postcode = 10100;
        $items = self::getAmphures($postcode, 1);
        print_r($items);
    }

    public function actionPostcode($area = 'district') {
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $postcode = empty($ids[0]) ? null : $ids[0];
            $filter = empty($ids[1]) ? null : $ids[1];
            if ($postcode != null) {
                if ($area == 'province') {
                    $items = self::getProvinces($postcode);
                } else if ($area == 'amphure') {
                    $items = self::getAmphures($postcode, $filter);
                } else if ($area == 'district') {
                    $items = self::getDistricts($postcode, $filter);
                }
                if (!empty($items)) {
                    $data = [];
                    foreach ($items as $key => $item) {
                        $data[] = ['id' => $key, 'name' => $item];
                    }
                    if (count($data) == 1) {
                        $selected = $data[0]['id'];
                    } else {
                        $selected = '';
                    }
                    return ['output' => $data, 'selected' => $selected];
                }
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    public function getDistricts($postcode, $amphure) {
        $areas = self::getAreaFromPostcode($postcode, ['amphure' => $amphure]);
        $data = [];
        if (!empty($areas)) {
            foreach ($areas as $area) {
                if ($amphure == $area['amphure']['id']) {
                    $data[$area['district']['id']] = $area['district']['name'];
                }
            }
        }
        return $data;
    }

    public function getAmphures($postcode, $province) {
        $areas = self::getAreaFromPostcode($postcode, ['province' => $province]);
        $data = [];
        if (!empty($areas)) {
            foreach ($areas as $area) {
                if ($province == $area['province']['id']) {
                    $data[$area['amphure']['id']] = $area['amphure']['name'];
                }
            }
        }
        return $data;
    }

    public function getProvinces($postcode) {
        $areas = self::getAreaFromPostcode($postcode);
        $data = [];
        if (!empty($areas)) {
            foreach ($areas as $area) {
                $data[$area['province']['id']] = $area['province']['name'];
            }
        }
        return $data;
    }

    public function getAreaFromPostcode($postcode, $filter = '') {
        if (!empty($postcode)) {
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

}
