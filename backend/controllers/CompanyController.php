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
    public $tabs = ['item', 'contact', 'transport'];

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

    public function actionItem($id = '', $mode = '') {

        Yii::$app->view->params['panel'] = 'control';
        Yii::$app->view->params['tabs'] = $this->tabs;
        Yii::$app->view->params['control'] = [
            'classname' => $this->company_type,
            'mode' => $mode,
        ];

        if ($mode == 'create') {
            Yii::$app->view->params['disabled'] = false;
            $model = new Company();

            $model->org = Yii::$app->session['organize'];
            $model->type = $this->company_type;
            $model->status = 1;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['item', 'id' => $model->id]);
            } else {
                $model->code = ControlBar::getNextCode($this->company_type);
            }
        } else if ($mode == 'update') {
            Yii::$app->view->params['disabled'] = false;
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['item', 'id' => $model->id]);
            }
        } else {
            Yii::$app->view->params['disabled'] = true;
            $model = $this->findModel($id);
        }

        return $this->render('/company/item', [
                    'model' => $model,
        ]);
    }

    public function actionAjax($sid) {


        return $this->renderAjax('/company/ajax', [
            'sid'=>$sid,
        ]);
    }

    public function actionTransport($id, $mode = '') {
        Yii::$app->view->params['panel'] = 'control';
        Yii::$app->view->params['tabs'] = $this->tabs;
        Yii::$app->view->params['control'] = [
            'classname' => $this->company_type,
            'mode' => $mode,
        ];

        Yii::$app->view->params['disabled'] = false;
        $model = $this->findModel($id);

        $query = CompanyContact::find()->where(['company_id' => $model->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $this->render('/company/transport', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionContact($id, $mode = '', $sid = '') {
        Yii::$app->view->params['panel'] = 'control';
        Yii::$app->view->params['tabs'] = $this->tabs;
        Yii::$app->view->params['control'] = [
            'classname' => $this->company_type,
            'mode' => $mode,
        ];

        $model = $this->findModel($id);

        if (!empty($sid)) {
            $model_contact = CompanyContact::findOne($sid);
        } else {
            $model_contact = new CompanyContact();
        }
        if ($mode == 'update') {
            Yii::$app->view->params['disabled'] = false;
            $model_contact->company_id = $id;
            if ($model_contact->load(Yii::$app->request->post()) && $model_contact->save()) {
                return $this->redirect(['contact', 'id' => $model->id]);
            }
        } else {
            Yii::$app->view->params['disabled'] = true;
        }

        $query = CompanyContact::find()->where(['company_id' => $model->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $this->render('/company/contact', [
                    'model' => $model,
                    'model_contact' => $model_contact,
                    'dataProvider' => $dataProvider,
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
            return $this->redirect(['item', 'id' => $model->id]);
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
