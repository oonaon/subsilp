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

    public $tabs = ['view', 'properties', 'images', 'stocks','prices'];

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                //          'contact_delete' => ['POST'],
                //          'contact_default' => ['POST'],
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

    public function actionView($id = '') {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        return $this->render('/product/item', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionCreate($id = '') {
        $this->layout = 'main_tab';
        $model = new Product();
        $model->org = Yii::$app->session['organize'];
        $model->kind = 1;
        $model->status = 1;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->initProperties();
            $model->initPrice();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('/product/item', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionUpdate($id = '') {
        $this->layout = 'main_tab';
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('/product/item', [
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
        $stocks=[];
        if(!empty($items)){
            foreach($items as $item){
                $stocks[$item->color][]=$item;
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

        $old_files = $model->images;
        if ($model->load(Yii::$app->request->post())) {
            $model->images = File::uploadMultiple($id, $model, 'images', $old_files);
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('/product/images', [
                    'model' => $model,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionImages_update($id) {
        $model = $this->findModel($id);
        $old_files = $model->images;
        if ($model->load(Yii::$app->request->post())) {
            $model->images = File::uploadMultiple($id, $model, 'images', $old_files);
            if ($model->save()) {
                return $this->redirect(['images', 'id' => $model->id]);
            }
        }

        return $this->renderAjax('/product/images_form', [
                    'model' => $model,
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
            'sort'=> ['defaultOrder' => ['price'=>SORT_ASC]]
        ]);
        return $this->render('/product/price', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'tabs' => $this->tabs,
        ]);
    }

    public function actionPrices_create($id) {
        $product = $this->findModel($id);
        $model = new ProductPrice();
        $model->product_id = $id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['prices', 'id' => $id]);
        }
        return $this->renderAjax('/product/price_form', [
                    'model' => $model,
                    'product' => $product,
        ]);
    }

    public function actionPrices_update($id, $sid) {
        $product = $this->findModel($id);
        $model = ProductPrice::findOne($sid);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['prices', 'id' => $id]);
        }
        return $this->renderAjax('/product/price_form', [
                    'model' => $model,
                    'product' => $product,
        ]);
    }

    public function actionPrices_delete($id, $sid) {
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

    public function actionProperties_create($id) {
        $product = $this->findModel($id);
        $model = new ProductProperties();
        $model->product_id = $id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['properties', 'id' => $id]);
        }
        return $this->renderAjax('/product/properties_form', [
                    'model' => $model,
                    'product' => $product,
        ]);
    }

    public function actionProperties_update($id, $sid) {
        $product = $this->findModel($id);
        $model = ProductProperties::findOne($sid);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['properties', 'id' => $id]);
        }
        return $this->renderAjax('/product/properties_form', [
                    'model' => $model,
                    'product' => $product,
        ]);
    }

    public function actionProperties_delete($id, $sid) {
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
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
