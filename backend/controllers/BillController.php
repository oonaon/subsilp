<?php

namespace backend\controllers;

use Yii;
use common\models\Bill;
use common\models\BillSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\ControlBar;
use yii\data\ActiveDataProvider;
use common\models\Product;

class BillController extends Controller {

    public $prefix_code = '';
    public $tabs = ['view', 'contact', 'location', 'files'];

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $this->layout = 'main_index';
        $searchModel = new BillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('/bill/index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id = '') {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        return $this->render('/bill/item', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionCreate($id = '') {
        $this->layout = 'main_tab';
        $model = new Bill();
        //$model->scenario='QT';
        $model->org = Yii::$app->session['organize'];
        $model->type = Bill::getTypeFromController();
        $model->date = date('Y-m-d');
        $model->status = '1';
        $model->remark = time();
        $model->generateNewCode();
        if ($model->load(Yii::$app->request->post()) && !Yii::$app->request->isPjax) {
            if ($model->save()) {
               return $this->redirect(['view', 'id' => $model->id]); 
            }
        }
        return $this->render('/bill/item', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionUpdate($id = '') {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && !Yii::$app->request->isPjax) {
            if ($model->save()) {
               return $this->redirect(['view', 'id' => $model->id]); 
            }
        }
        return $this->render('/bill/item', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionFind() {
        $code = Yii::$app->request->post('find_code');
        $code = strtoupper($code);
        $model = Company::findOne(['code' => $code]);
        if ($model) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            Yii::$app->session->setFlash('warning', Yii::t('backend/flash', 'not_found'));
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    protected function findModel($id) {
        if (($model = Bill::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
