<?php

namespace backend\controllers;

use Yii;
use common\models\Company;
use common\models\CompanySearch;
use common\models\CompanyContact;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\ControlBar;
use yii\data\ActiveDataProvider;

class CompanyController extends Controller {

    public $company_type = 'cus';
    public $tabs = ['view', 'contact', 'transport'];

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
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $this->company_type);
        return $this->render('/company/index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'company_type' => $this->company_type,
        ]);
    }

    public function actionView($id = '') {
        $this->layout='main_tab';
        $model = $this->findModel($id);
        return $this->render('/company/item', [
                    'model' => $model,
                    'company_type' => $this->company_type,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionCreate($id = '') {
        $this->layout='main_tab';
        $model = new Company();
        $model->org = Yii::$app->session['organize'];
        $model->type = $this->company_type;
        $model->status = 1;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
        $this->layout='main_tab';
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('/company/item', [
                    'model' => $model,
                    'company_type' => $this->company_type,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionContact($id) {
        $this->layout='main_tab';
        $model = $this->findModel($id);
        $query = CompanyContact::find()->where(['company_id' => $model->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $this->render('/company/contact', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'company_type' => $this->company_type,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionContact_create($id) {
        $this->layout='main_tab';
        $model_contact = new CompanyContact();
        $model_contact->company_id = $id;
        if ($model_contact->load(Yii::$app->request->post()) && $model_contact->save()) {
            return $this->redirect(['contact', 'id' => $id]);
        }
        return $this->renderAjax('/company/contact_form', [
                    'model_contact' => $model_contact,
        ]);
    }

    public function actionContact_update($id, $sid) {
        $this->layout='main_tab';
        $model_contact = CompanyContact::findOne($sid);
        if ($model_contact->load(Yii::$app->request->post()) && $model_contact->save()) {
            return $this->redirect(['contact', 'id' => $id]);
        }
        return $this->renderAjax('/company/contact_form', [
                    'model_contact' => $model_contact,
        ]);
    }

    public function actionContact_delete($id, $sid) {
        $model_contact = CompanyContact::findOne($sid)->delete();
        ;
        return $this->redirect(['contact', 'id' => $id]);
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
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
