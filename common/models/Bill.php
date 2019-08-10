<?php

namespace common\models;

use Yii;
use common\components\CActiveRecord;
use common\components\Code;
use common\models\Product;

class Bill extends CActiveRecord {

    public static function tableName() {
        return 'bill';
    }

    public function rules() {
        return [
            [['org', 'code', 'type', 'date', 'company_id'], 'required'],
            [['code'], 'string', 'length' => 9, 'skipOnEmpty' => false],
            [['date', 'duedate'], 'safe'],
            [['company_id', 'ref_id', 'credit', 'discount_percent', 'vat_percent', 'location_id', 'contact_id', 'salesman'], 'integer'],
            [['pre_total', 'discount_total', 'sub_total', 'amount_novat', 'amount_vat', 'vat_total', 'total'], 'number'],
            [['remark'], 'string'],
            [['org', 'type'], 'string', 'max' => 100],
            [['code', 'vat_type'], 'string', 'max' => 20],
            [['reference'], 'string', 'max' => 255],
            [['transport', 'status'], 'string', 'max' => 5],
                //     [['item_code', 'item_code_old', 'item_sub', 'item_stock'], 'each', 'rule' => ['string', 'max' => 20]],
                //     ['item_caption', 'each', 'rule' => ['string', 'max' => 255]],
                //     [['item_quantity', 'item_price', 'item_price_suggest'], 'each', 'rule' => ['integer', 'min' => 0]],
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        //$scenarios['QT'] = ['code','date'];
        //$scenarios['IV'] = ['patient_id', 'weight', 'blood_pressure', 'pre_diag'];
        return $scenarios;
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('common/model', 'id'),
            'org' => Yii::t('common/model', 'org'),
            'code' => Yii::t('common/model', 'bill_code'),
            'type' => Yii::t('common/model', 'type'),
            'date' => Yii::t('common/model', 'date'),
            'company_id' => Yii::t('common/model', 'company'),
            'ref_id' => Yii::t('common/model', 'ref_id'),
            'vat_type' => Yii::t('common/model', 'vat_type'),
            'reference' => Yii::t('common/model', 'reference'),
            'credit' => Yii::t('common/model', 'credit'),
            'duedate' => Yii::t('common/model', 'duedate'),
            'pre_total' => Yii::t('common/model', 'pre_total'),
            'discount_percent' => Yii::t('common/model', 'discount_percent'),
            'discount_total' => Yii::t('common/model', 'discount_total'),
            'sub_total' => Yii::t('common/model', 'sub_total'),
            'amount_novat' => Yii::t('common/model', 'amount_novat'),
            'amount_vat' => Yii::t('common/model', 'amount_vat'),
            'vat_percent' => Yii::t('common/model', 'vat_percent'),
            'vat_total' => Yii::t('common/model', 'vat_total'),
            'total' => Yii::t('common/model', 'total'),
            'remark' => Yii::t('common/model', 'remark'),
            'transport' => Yii::t('common/model', 'transport'),
            'location_id' => Yii::t('common/model', 'location'),
            'contact_id' => Yii::t('common/model', 'contact_person'),
            'salesman' => Yii::t('common/model', 'salesman'),
            'status' => Yii::t('common/model', 'status'),
        ];
    }

    public static function getTypeFromController() {
        $controller = Yii::$app->controller->id;
        if ($controller == 'quotation') {
            $type = 'QT';
        } else if ($controller == 'saleorder') {
            $type = 'SO';
        } else if ($controller == 'invoice') {
            $type = 'IV';
        } else {
            $type = $controller;
        }
        return $type;
    }

    public function generateNewCode() {
        $last = Bill::find()->where('(code like :prefix) and (org like :org)', [':org' => '%' . Yii::$app->session['organize'] . '%', ':prefix' => Code::frontBill() . '%'])->orderBy(['abs(substring(code, 3))' => SORT_DESC])->select('code')->asArray()->one();
        $this->code = Code::generateBillCode($last['code']);
    }

    public function beforeSave($insert) {

        return parent::beforeSave($insert);
    }

    public function afterFind() {
        parent::afterFind();
    }

    public function beforeDelete() {
        //  File::deleteFileAll($this->files);
        //  File::deleteDir($id);
        return parent::beforeDelete();
    }

    public function getCompany() {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    public function getItem() {
        return $this->hasMany(BillItem::className(), ['bill_id' => 'id']);
    }

    public function checkContactDefault() {
        if (!empty($this->company->contacts)) {
            if (empty($this->contact_id)) {
                foreach ($this->company->contacts as $con) {
                    if (!empty($con->item_default)) {
                        $this->contact_id = $con->id;
                        break;
                    }
                }
            } else {
                $default = '';
                $hit = false;
                foreach ($this->company->contacts as $con) {
                    if (!empty($con->item_default)) {
                        $default = $con->id;
                    }
                    if ($con->id == $this->contact_id) {
                        $hit = true;
                    }
                }
                if (!$hit) {
                    $this->contact_id = $default;
                }
            }
        } else {
            $this->contact_id = '';
        }
    }

    public function getSubItem($id) {
        $model = Product::findOne($id);
        if (!empty($model)) {
            return $model->getSubProduct();
        } else {
            return [];
        }
    }

    public function getStockItem($id, $sub = '') {
        $model = Product::findOne($id);
        if (!empty($model)) {
            return $model->getStockProduct($sub);
        } else {
            return [];
        }
    }

    public function itemsLoad($data) {
        $items = $this->item;
        $loads = empty($data['BillItem']) ? [] : $data['BillItem'];
        if (!empty($loads)) {
            $arr = [];
            foreach ($loads as $key => $load) {
                if (!empty($load['product_id'])) {
                    $model = new BillItem;
                    if (!empty($load['id'])) {
                        $model = BillItem::findOne($load['id']);
                    }
                    foreach ($load as $att => $val) {
                        $model[$att] = $val;
                    }
                    $arr[] = $model;
                }
            }
            $items = $arr;
        }
        return $items;
    }

    public function itemsProcess($items = []) {

        if (!empty($items)) {
            $arr = [];
            foreach ($items as $key => $item) {
                if (!empty($item['product_id'])) {
                    $product = Product::findOne($item['product_id']);
                    if (!empty($product)) {
                        if ($item['old'] != $item['product_id']) {      // changed items
                            $item['caption'] = $product->caption;
                            if (!isset($item['quantity'])) {
                                $item['quantity'] = 1;
                            }
                            $item['sub'] = $product->getSubDefault();
                            $item['discount'] = 0;
                        }
                        $item['price_suggest'] = $product->getProductPrice($this->company_id, $item['quantity']);



                        if (!empty($item['price']) && !empty($item['quantity'])) {
                            $item['total'] = ($item['quantity'] * $item['price']) - $item['discount'];
                        } else {
                            $item['total'] = 0;
                        }
                        $item['old'] = $item['product_id'];
                        $arr[] = $item;
                    }
                }
            }
            foreach ($arr as $key => $item) {
                $arr[$key]['sort_order'] = $key;
            }
            return $arr;
        }
        return $items;
    }

}
