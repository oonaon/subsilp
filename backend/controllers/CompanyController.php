<?php

namespace backend\controllers;

use Yii;
use common\models\Company;
use common\models\CompanySearch;
use common\models\CompanyContact;
use common\models\CompanyLocation;
use common\models\File;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\components\ControlBar;
use yii\data\ActiveDataProvider;
use common\components\CActiveForm;

class CompanyController extends Controller {

    public $tabs = ['view', 'contact', 'location', 'files'];

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'contact-delete' => ['POST'],
                    'contact-default' => ['POST'],
                    'location-delete' => ['POST'],
                    'location-default' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $this->layout = 'main_index';
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('/company/index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        return $this->render('/company/update', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionUpdate($id = '') {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        if ($model->isNewRecord) {
            $model->org = Yii::$app->session['organize'];
            $model->type = Company::getTypeFromController();
            $model->status = 1;
            $model->generateNewCode();
        }
        if ($model->load(Yii::$app->request->post()) && !Yii::$app->request->isPjax) {
            if ($model->save()) {
                $model->updateLocation();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('/company/update', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionDelete($id) {
        CompanyContact::deleteEachAll(['company_id' => $id]);
        CompanyLocation::deleteEachAll(['company_id' => $id]);
        $model = $this->findModel($id);
        $model->delete();
        return $this->redirect(['index']);
    }

    // ***** FILES *****

    public function actionFiles($id) {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        return $this->render('/company/files', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionFilesUpdate($id) {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        $old_files = $model->files;
        if ($model->load(Yii::$app->request->post()) && !Yii::$app->request->isPjax) {
            $model->files = File::uploadMultiple($id, $model, 'files', $old_files);
            if ($model->save()) {
                return $this->redirect(['files', 'id' => $model->id]);
            }
        }
        return $this->render('/company/files_update', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    // ***** LOCATION *****

    public function actionLocation($id) {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        $query = CompanyLocation::find()->where(['company_id' => $model->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            //'sort' => ['defaultOrder' => ['id' => SORT_ASC]],
        ]);
        return $this->render('/company/location', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionLocationUpdate($id, $sid = '') {
        $this->layout = 'main_tab';
        $model = $this->findLocation($sid);
        if ($model->isNewRecord) {
            $model->company_id = $id;
            $model->item_default = 0;
            $model->item_fix = 0;
            $model->latitude = 0;
            $model->longitude = 0;
            
            $contact = CompanyContact::findOne(['company_id' => $id, 'item_default' => 1]);
            if (!empty($contact->id)) {
                $model->contact_id = $contact->id;
            }
            
            $item = CompanyLocation::findOne(['company_id' => $id, 'item_default' => 1]);
            if (empty($item)) {
                $model->item_default = 1;
            }
        }
        $old_map = $model->map;
        if ($model->load(Yii::$app->request->post()) && !Yii::$app->request->isPjax) {
            $model->map = File::uploadMultiple($id, $model, 'map', $old_map);
            if ($model->save()) {
                return $this->redirect(['location', 'id' => $id]);
            }
        }
        return $this->render('/company/location_update', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionLocationDefault($id, $sid) {
        CompanyLocation::updateAll(['item_default' => 0], ['company_id' => $id]);
        $model = CompanyLocation::findOne($sid);
        $model->item_default = 1;
        $model->save();
        return $this->redirect(['location', 'id' => $id]);
    }

    public function actionLocationDelete($id, $sid) {
        $model = CompanyLocation::findOne($sid);
        if (!$model->item_fix) {
            $model->delete();
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
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionContactUpdate($id, $sid = '') {
        $this->layout = 'main_tab';
        $model = $this->findContact($sid);
        if ($model->isNewRecord) {
            $model->company_id = $id;
            $model->item_default = 0;
            $item = CompanyContact::findOne(['company_id' => $id, 'item_default' => 1]);
            if (empty($item)) {
                $model->item_default = 1;
            }
        }
        if ($model->load(Yii::$app->request->post()) && !Yii::$app->request->isPjax) {
            if ($model->save()) {
                return $this->redirect(['contact', 'id' => $id]);
            }
        }
        return $this->render('/company/contact_update', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionContactDefault($id, $sid) {
        CompanyContact::updateAll(['item_default' => 0], ['company_id' => $id]);
        $model = CompanyContact::findOne($sid);
        $model->item_default = 1;
        $model->save();
        return $this->redirect(['contact', 'id' => $id]);
    }

    public function actionContactDelete($id, $sid) {
        CompanyContact::findOne($sid)->delete();
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
        if(empty($id)){
            return new Company;
        } else if (($model = Company::findOne($id)) !== null) {
            return $model;
        }
        throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function findContact($id) {
        if(empty($id)){
            return new CompanyContact;
        } else if (($model = CompanyContact::findOne($id)) !== null) {
            return $model;
        }
        throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function findLocation($id) {
        if(empty($id)){
            return new CompanyLocation;
        } else if (($model = CompanyLocation::findOne($id)) !== null) {
            return $model;
        }
        throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
