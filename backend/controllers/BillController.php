<?php

namespace backend\controllers;

use Yii;
use common\models\Bill;
use common\models\BillSearch;
use common\models\BillItem;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\ControlBar;
use yii\data\ActiveDataProvider;
use common\models\Product;
use yii\base\Model;

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
        $model_items = $model->item;
        return $this->render('/bill/update', [
                    'model' => $model,
                    'model_items' => $model_items,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionUpdate($id = '') {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        $model_items = $model->item;

        //$model->scenario='QT';
        if ($model->isNewRecord) {
            $model->org = Yii::$app->session['organize'];
            $model->type = Bill::getTypeFromController();
            $model->date = date('Y-m-d');
            $model->status = '1';
            $model->remark = time();
            $model->generateNewCode();
        }

        $data = Yii::$app->request->post();
        if ($model->load($data)) {
            $model_items = $model->itemsLoad($data);
            $model->checkContactDefault();
            if (!Yii::$app->request->isPjax) {
                if ($model->validate() && Model::validateMultiple($model_items)) {
                    $model_items = $model->itemsProcess($model_items);
                    $transaction = Yii::$app->db->beginTransaction();
                    try {

                        if ($model->save()) {
                            if (!empty($model_items)) {
                                $items_id = [];
                                foreach ($model_items as $item) {
                                    $item->bill_id = $model->id;
                                    if ($item->save()) {
                                        $items_id[] = $item->id;
                                    }
                                }
                            }
                            BillItem::deleteAll(['and', 'bill_id = :bill_id', ['not in', 'id', $items_id]], [':bill_id' => $model->id]);
                        }

                        $transaction->commit();
                    } catch (Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                $model_items = $model->itemsProcess($model_items);
            }
        }
        return $this->render('/bill/update', [
                    'model' => $model,
                    'model_items' => $model_items,
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
        if (empty($id)) {
            return new Bill;
        } else if (($model = Bill::findOne($id)) !== null) {
            return $model;
        }
        throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
