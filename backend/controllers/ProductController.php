<?php

namespace backend\controllers;

use Yii;
use common\models\Product;
use common\models\ProductSearch;
use common\models\ProductProperties;
use common\models\ProductStock;
use common\models\ProductPrice;
use common\models\File;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\ControlBar;
use yii\data\ActiveDataProvider;
use common\components\CActiveForm;

class ProductController extends Controller {

    public $tabs = ['view', 'properties', 'images', 'stocks', 'prices'];

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'prices-delete' => ['POST'],
                    'properties-delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $this->layout = 'main_index';
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('/product/index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        return $this->render('/product/update', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionUpdate($id = '') {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        if ($model->isNewRecord) {
            $model->org = Yii::$app->session['organize'];
            $model->kind = 1;
            $model->status = 1;
        }
        if ($model->load(Yii::$app->request->post()) && !Yii::$app->request->isPjax) {
            if ($model->save()) {
                $model->initProperties();
                $model->initPrice();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('/product/update', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    // *****************************

    public function actionStocks($id) {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        $items = ProductStock::find()->where(['product_id' => $model->id])->all();
        $stocks = [];
        if (!empty($items)) {
            foreach ($items as $item) {
                $stocks[$item->sub][] = $item;
            }
        }
        return $this->render('/product/stock', [
                    'model' => $model,
                    'stocks' => $stocks,
                    'tabs' => $this->tabs,
        ]);
    }

    // ***** FILES *****

    public function actionImages($id) {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        return $this->render('/product/images', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionImagesUpdate($id) {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        $old_files = $model->images;
        if ($model->load(Yii::$app->request->post()) && !Yii::$app->request->isPjax) {
            $model->images = File::uploadMultiple($id, $model, 'images', $old_files);
            if ($model->save()) {
                return $this->redirect(['images', 'id' => $model->id]);
            }
        }
        return $this->render('/product/images_update', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    // ****************************

    public function actionPrices($id) {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        $query = ProductPrice::find()->where(['product_id' => $model->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => ['defaultOrder' => ['price' => SORT_ASC]],
        ]);
        return $this->render('/product/price', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionPricesUpdate($id, $sid = '') {
        $this->layout = 'main_tab';
        $model = $this->findPrice($sid);
        if ($model->isNewRecord) {
            $model->product_id = $id;
            $model->quantity = 0;
        }
        if ($model->load(Yii::$app->request->post()) && !Yii::$app->request->isPjax) {
            if ($model->save()) {
                return $this->redirect(['prices', 'id' => $id]);
            }
        }
        return $this->render('/product/price_update', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionPricesDelete($id, $sid) {
        ProductPrice::findOne($sid)->delete();
        return $this->redirect(['prices', 'id' => $id]);
    }

    // ***** PROPERTIES *****

    public function actionProperties($id) {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        $query = ProductProperties::find()->where(['product_id' => $model->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $this->render('/product/properties', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionPropertiesUpdate($id, $sid='') {
        $this->layout = 'main_tab';
        $model = $this->findProperties($sid);
        if ($model->isNewRecord) {
            $model->product_id = $id;
        }
        if ($model->load(Yii::$app->request->post()) && !Yii::$app->request->isPjax) {
            if ($model->save()) {
                return $this->redirect(['properties', 'id' => $id]);
            }
        }
        return $this->render('/product/properties_update', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionPropertiesDelete($id, $sid) {
        ProductProperties::findOne($sid)->delete();
        return $this->redirect(['properties', 'id' => $id]);
    }

    // ***** OTHER *****

    public function actionFind() {
        $code = Yii::$app->request->post('find_code');
        $code = strtoupper($code);
        $model = Product::findOne(['code' => $code]);
        if ($model) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            Yii::$app->session->setFlash('warning', Yii::t('backend/flash', 'not_found'));
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    protected function findModel($id) {
        if (empty($id)) {
            return new Product;
        } else if (($model = Product::findOne($id)) !== null) {
            return $model;
        }
        throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function findProperties($id) {
        if (empty($id)) {
            return new ProductProperties;
        } else if (($model = ProductProperties::findOne($id)) !== null) {
            return $model;
        }
        throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function findPrice($id) {
        if (empty($id)) {
            return new ProductPrice;
        } else if (($model = ProductPrice::findOne($id)) !== null) {
            return $model;
        }
        throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
