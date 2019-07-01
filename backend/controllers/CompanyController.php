<?php

namespace backend\controllers;

use Yii;
use common\models\Company;
use common\models\CompanySearch;
use common\models\CompanyContact;
use common\models\CompanyLocation;
use common\models\File;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\ControlBar;
use yii\data\ActiveDataProvider;
use common\components\CActiveForm;

class CompanyController extends Controller {

    public $company_type = 'cus';
    public $tabs = ['view', 'contact', 'location'];

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'contact_delete' => ['POST'],
                    'contact_default' => ['POST'],
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
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    // ***** LOCATION *****

    public function actionLocation($id) {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        $query = CompanyLocation::find()->where(['company_id' => $model->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $this->render('/company/location', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'company_type' => $this->company_type,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionLocation_create($id) {
        $this->layout = 'main_tab';
        $model_location = new CompanyLocation();
        $model_location->item_default = 0;
        $model_location->item_fix = 0;
        $contact_default = CompanyContact::findOne(['company_id' => $id, 'item_default' => 1]);
        if (!empty($contact_default->id)) {
            $model_location->contact_id = $contact_default->id;
        }
        $model_location->company_id = $id;
        if ($model_location->load(Yii::$app->request->post())) {
            $model_location->map = File::uploadMultiple($id, $model_location, 'map');
            if ($model_location->save()) {
                return $this->redirect(['location', 'id' => $id]);
            }
        }
        return $this->renderAjax('/company/location_form', [
                    'model_location' => $model_location,
                    'id' => $id,
        ]);
    }

    public function actionLocation_update($id, $sid) {
        $this->layout = 'main_tab';
        $model_location = CompanyLocation::findOne($sid);   
        $old_map=$model_location->map;
        if ($model_location->load(Yii::$app->request->post())) {
            File::deleteFileDifferent($old_map,$model_location->map);
            $model_location->map = File::uploadMultiple($id, $model_location, 'map');
            if ($model_location->save()) {
                return $this->redirect(['location', 'id' => $id]);
            }
        }
        return $this->renderAjax('/company/location_form', [
                    'model_location' => $model_location,
                    'id' => $id,
        ]);
    }

    public function actionLocation_default($id, $sid) {
        $this->layout = 'main_tab';
        CompanyLocation::updateAll(['item_default' => 0], ['company_id' => $id]);
        $model_location = CompanyLocation::findOne($sid);
        $model_location->item_default = 1;
        $model_location->save();
        return $this->redirect(['location', 'id' => $id]);
    }

    public function actionLocation_delete($id, $sid) {
        $model_location = CompanyLocation::findOne($sid);
        if (!$model_location->item_fix) {
            $model_location->delete();
        }
        return $this->redirect(['location', 'id' => $id]);
    }

    // ***** CONTACT *****

    public function actionContact($id) {
        $this->layout = 'main_tab';
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
        $this->layout = 'main_tab';
        $model_contact = new CompanyContact();
        $num = CompanyContact::find()->where(['company_id' => $id])->count();
        if (empty($num)) {
            $model_contact->item_default = 1;
        }
        $model_contact->company_id = $id;
        if ($model_contact->load(Yii::$app->request->post()) && $model_contact->save()) {
            return $this->redirect(['contact', 'id' => $id]);
        }
        return $this->renderAjax('/company/contact_form', [
                    'model_contact' => $model_contact,
        ]);
    }

    public function actionContact_update($id, $sid) {
        $this->layout = 'main_tab';
        $model_contact = CompanyContact::findOne($sid);
        if ($model_contact->load(Yii::$app->request->post()) && $model_contact->save()) {
            return $this->redirect(['contact', 'id' => $id]);
        }
        return $this->renderAjax('/company/contact_form', [
                    'model_contact' => $model_contact,
        ]);
    }

    public function actionContact_default($id, $sid) {
        $this->layout = 'main_tab';
        CompanyContact::updateAll(['item_default' => 0], ['company_id' => $id]);
        $model_contact = CompanyContact::findOne($sid);
        $model_contact->item_default = 1;
        $model_contact->save();
        return $this->redirect(['contact', 'id' => $id]);
    }

    public function actionContact_delete($id, $sid) {
        $model_contact = CompanyContact::findOne($sid)->delete();
        ;
        return $this->redirect(['contact', 'id' => $id]);
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
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
