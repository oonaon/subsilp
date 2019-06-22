<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\MessageSource;

class FileController extends Controller {

    public function behaviors() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
        ];
    }

    public function actionLanguagecategory($input = null) {
        $out = ['results' => ['id' => '', 'text' => '']];

        if (!is_null($input)) {
            $where = "category LIKE '%" . $input . "%'";
        } else {
            $where = '';
        }

        $model = MessageSource::find()
                ->groupBy(['category'])
                ->select(['category as text', 'id as id'])
                ->groupBy('category')
                ->where($where)
                ->asArray()
                ->all();

        $out['results'] = $model;

        return $out;
    }

}
