<?php

namespace common\models;

use Yii;
use common\components\CActiveRecord;
use common\components\Code;

class Bill extends CActiveRecord {

    //public $company;
    
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
            'code' => Yii::t('common/model', 'code'),
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
            'contact_id' => Yii::t('common/model', 'contact'),
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
    
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

}
