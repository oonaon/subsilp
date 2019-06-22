<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\components\Area;


class AreaController extends Controller {

    public function behaviors() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
        ];
    }

    public function actionPostcode($postcode) {
        $data = [];
        $selected = '';
        //$data[] = ['id' => 0, 'name' => Yii::t('backend/general', 'select')];
        if (!empty($postcode)) {
            $items = Area::getDistricts($postcode);
            if (!empty($items)) {
                foreach ($items as $key => $item) {
                    $data[] = ['id' => $key, 'name' => $item];
                }
                if (count($data) == 1) {
                    $selected = $data[0]['id'];
                }
            }
        }
        return ['output' => $data, 'selected' => $selected];
    }

    

}
