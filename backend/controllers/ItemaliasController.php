<?php

namespace backend\controllers;

use Yii;
use common\models\ItemAlias;
use common\models\ItemAliasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\ControlBar;
use yii\data\ActiveDataProvider;
use common\components\CActiveForm;

class ItemaliasController extends Controller {

    public $type = 'company_rank';

    /**
     * {@inheritdoc}
     */
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
        $this->layout = 'main_sub';
        $searchModel = new ItemAliasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$this->type);
        return $this->render('/itemalias/index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                 
        ]);
    }

    public function actionView($id = '') {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        return $this->render('/company/item', [
                    'model' => $model,
                    'company_type' => $this->company_type,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionCreate($id = '') {
        $this->layout = 'main_tab';
        $model = new Company();
        $model->org = Yii::$app->session['organize'];
        $model->type = $this->company_type;
        $model->status = 1;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->updateLocation();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->code = ControlBar::getNextCode($this->company_type);
        }
        return $this->render('/company/item', [
                    'model' => $model,
                    'company_type' => $this->company_type,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionUpdate($id = '') {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        //print_r(Yii::$app->request->post());
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->updateLocation();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('/company/item', [
                    'model' => $model,
                    'company_type' => $this->company_type,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionDelete($id) {
        CompanyContact::deleteEachAll(['company_id' => $id]);
        CompanyLocation::deleteEachAll(['company_id' => $id]);
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    // ***** OTHER *****

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
        if (($model = ItemAlias::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
