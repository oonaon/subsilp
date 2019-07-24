<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\MessageSource;
use common\models\Company;
use common\models\AreaDistricts;

class AjaxController extends Controller {

    public function behaviors() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
        ];
    }

    public function actionLanguagecategory($input = null) {
        $model = MessageSource::find()
                ->select(['id', 'category'])
                ->groupBy(['category'])
                ->where(['LIKE', 'category', $input])
                ->all();
        return self::convertOutput($model, ['category']);
    }

    public function actionCompany($input = null, $type = 'cus') {
        $model = Company::find()
                ->select(['id', 'code', 'name'])
                ->where(['LIKE', 'type', $type])
                ->andWhere('(code like :input) OR (name like :input)', [':input' => '%' . $input . '%'])
                ->all();
        return self::convertOutput($model, ['code', 'name']);
    }

    public function actionPostcode($input = null) {
        $model = AreaDistricts::find()
                ->where(['postcode' => $input])
                ->all();
        $arr = [];
        foreach ($model as $district) {
            $item = [
                'id' => $district['id'],
                'postcode' => $district['postcode'],
                'text' => $district->getSelectLabel(),
            ];
            $arr[] = $item;
        }
        return self::convertOutput($arr, ['postcode','text']);
    }

    public static function getData($name, $id) {
        if ($name == 'languagecategory') {
            $model = MessageSource::findOne($id);
            $text = self::labelFormat($model, ['category']);
        } else if ($name == 'company') {
            $model = Company::findOne($id);
            $text = self::labelFormat($model, ['code', 'name']);
        } else if ($name == 'postcode') {
            $model = AreaDistricts::findOne($id);
            $text = $model->postcode.' - '.$model->getSelectLabel();
        }
        if (!empty($model)) {
            return [$id => $text];
        } else {
            return null;
        }
    }

    public static function convertOutput($model, $atts) {
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!empty($model)) {
            $results = [];
            foreach ($model as $item) {
                $text = self::labelFormat($item, $atts);
                $results[] = ['id' => $item['id'], 'text' => $text,];
            }
            $out = ['results' => $results];
        }
        return $out;
    }

    public static function labelFormat($model, $atts = ['code'], $glue = ' - ') {
        $text = [];
        foreach ($atts as $att) {
            $text[] = $model[$att];
        }
        $text = implode($glue, $text);
        return $text;
    }

}
