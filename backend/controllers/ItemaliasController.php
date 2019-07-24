<?php

namespace backend\controllers;

use Yii;
use common\models\ItemAlias;
use common\models\ItemAliasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use common\components\CActiveForm;

class ItemaliasController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'item_delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $this->layout = 'main_sub';
        $category = ItemAlias::find()->groupBy('category')->select('category')->asArray()->all();
        $searchModel = new ItemAliasSearch();
        $select_category = $id = Yii::$app->request->get('category', 'color');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $select_category);
        return $this->render('/itemalias/index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'category' => $category,
                    'select_category' => $select_category,
        ]);
    }

    public function actionItem_create($category) {
        $model = new ItemAlias();
        $model->category=$category;
        $model->status=1;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'category' => $model->category]);
        }
        return $this->renderAjax('/itemalias/item_form', [
                    'model' => $model,
        ]);
    }

    public function actionItem_update($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'category' => $model->category]);
        }
        return $this->renderAjax('/itemalias/item_form', [
                    'model' => $model,
        ]);
    }

    public function actionItem_delete($id) {
        $model = $this->findModel($id);
        $cat=$model->category;
        $model->delete();
        return $this->redirect(['index', 'category' => $cat]);
    }

    protected function findModel($id) {
        if (($model = ItemAlias::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
