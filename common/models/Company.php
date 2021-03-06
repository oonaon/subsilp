<?php

namespace common\models;

use Yii;
use common\models\AreaDistricts;
use common\models\CompanyLocation;
use common\models\Area;
use common\models\ItemAlias;
use common\components\CActiveRecord;
use common\components\Code;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property string $org
 * @property string $code
 * @property string $type
 * @property string $kind
 * @property string $name
 * @property string $tax
 * @property int $branch
 * @property string $tel
 * @property string $fax
 * @property string $email
 * @property string $website
 * @property string $address
 * @property string $amphure
 * @property string $district
 * @property string $province
 * @property string $postcode
 * @property int $credit
 * @property string $payment
 * @property string $memo
 * @property int $salesman
 * @property int $transport
 * @property string $transport_note
 * @property string $rank
 * @property string $status
 */
class Company extends CActiveRecord {

    public $files_upload;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['org', 'code', 'name', 'type'], 'required'],
            ['district', 'required',
                'when' => function($model) {
                    return !empty($model->postcode);
                },
                'isEmpty' => function ($value) {
                    return empty($value) || !is_numeric($value);
                }],
            //[['district'], 'integer', 'min' => 1, 'message' => Yii::t('backend/general', 'select') . ' {attribute} '],
            ['code', 'unique', 'targetAttribute' => ['code']],
            [['branch', 'credit', 'salesman'], 'integer'],
            [['branch', 'credit', 'salesman'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 1],
            [['address', 'payment', 'memo', 'transport_note', 'files'], 'string'],
            [['postcode', 'transport', 'rank', 'status'], 'string', 'max' => 5],
            [['code', 'kind'], 'string', 'max' => 20],
            [['name', 'tel', 'fax', 'email', 'website'], 'string', 'max' => 100],
            [['tax'], 'string', 'max' => 13],
            [['email'], 'email'],
            [['files_upload'], 'file', 'maxFiles' => 10, 'skipOnEmpty' => true, 'extensions' => ['jpg', 'png', 'pdf', 'zip', 'ai']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('common/model', 'id'),
            'org' => Yii::t('common/model', 'org'),
            'code' => Yii::t('common/model', 'code'),
            'type' => Yii::t('common/model', 'type'),
            'kind' => Yii::t('common/model', 'kind'),
            'name' => Yii::t('common/model', 'name'),
            'tax' => Yii::t('common/model', 'tax'),
            'branch' => Yii::t('common/model', 'branch'),
            'tel' => Yii::t('common/model', 'tel'),
            'fax' => Yii::t('common/model', 'fax'),
            'email' => Yii::t('common/model', 'email'),
            'website' => Yii::t('common/model', 'website'),
            'address' => Yii::t('common/model', 'address'),
            'amphure' => Yii::t('common/model', 'amphure'),
            'district' => Yii::t('common/model', 'district'),
            'province' => Yii::t('common/model', 'province'),
            'postcode' => Yii::t('common/model', 'postcode'),
            'credit' => Yii::t('common/model', 'credit'),
            'payment' => Yii::t('common/model', 'payment'),
            'memo' => Yii::t('common/model', 'memo'),
            'salesman' => Yii::t('common/model', 'salesman'),
            'transport' => Yii::t('common/model', 'transport'),
            'transport_note' => Yii::t('common/model', 'transport_note'),
            'rank' => Yii::t('common/model', 'rank'),
            'files' => Yii::t('common/model', 'files'),
            'status' => Yii::t('common/model', 'status'),
        ];
    }

    public function fullAddress() {
        return Area::fullAddress($this);
    }

    public function updateLocation() {
        $model_location = CompanyLocation::findOne(['company_id' => $this->id, 'item_fix' => 1]);
        if (empty($model_location)) {
            $model_location = new CompanyLocation();
            $model_location->company_id = $this->id;
            $model_location->contact_id = 0;
            $model_location->item_default = 1;
            $model_location->item_fix = 1;
        }
        $model_location->address = $this->address;
        $model_location->district = $this->district;
        $model_location->amphure = $this->amphure;
        $model_location->province = $this->province;
        $model_location->postcode = $this->postcode;
        return $model_location->save(false);
    }

    public function beforeSave($insert) {
        $this->org = implode(',', $this->org);
        $this->type = implode(',', $this->type);

        $district = AreaDistricts::findOne($this->district);
        if (empty($district)) {
            $this->district = 0;
            $this->amphure = 0;
            $this->province = 0;
            $this->postcode = '';
        } else {
            $this->amphure = $district->amphure_id;
            $this->province = $district->amphure->province_id;
            $this->postcode = $district->postcode;
        }
        return parent::beforeSave($insert);
    }

    public function afterFind() {
        parent::afterFind();
        $this->org = explode(',', $this->org);
        $this->type = explode(',', $this->type);
    }

    public function beforeDelete() {
        File::deleteFileAll($this->files);
        File::deleteDir($this->id);
        return parent::beforeDelete();
    }

    public function getContacts() {
        return $this->hasMany(CompanyContact::className(), ['company_id' => 'id']);
    }

    public function getFullName($full = false) {
        $kind_name = ItemAlias::getLabel('company_kind', $this->kind);
        if ($this->kind == 1) {
            if ($full) {
                return Yii::t('common/general', 'prefix_co_ltd') . ' ' . $this->name . ' ' . Yii::t('common/general', 'suffix_co_ltd');
            } else {
                return $kind_name . ' ' . $this->name;
            }
        } else if ($this->kind == 2) {
            if ($full) {
                return Yii::t('common/general', 'prefix_part_ltd') . ' ' . $this->name . ' ' . Yii::t('common/general', 'suffix_part_ltd');
            } else {
                return $kind_name . ' ' . $this->name;
            }
        } else if ($this->kind == 3) {
            if ($full) {
                return Yii::t('common/general', 'prefix_pub_ltd') . ' ' . $this->name . ' ' . Yii::t('common/general', 'suffix_pub_ltd');
            } else {
                return $kind_name . ' ' . $this->name;
            }
        } else if ($this->kind == 4) {
            return $kind_name . $this->name;
        } else {
            return $this->name;
        }
    }

    public function getFullAddress() {
        $address = '';
        if (!empty($this->district)) {
            $district = AreaDistricts::findOne($this->district);
            $address = $this->address . ' ' . $district->getAddress();
        }
        return $address;
    }
    
    public function getBranch(){
        if($this->branch==0){
            return Yii::t('common/model', 'branch_head');
        } else if($this->branch>0){
            return Yii::t('common/model', 'branch_at').' '.str_pad($this->branch,4,'0',STR_PAD_LEFT);
        } else {
            return '';
        }
    }

    public function getFullDetail() {
        $detail = '<b>' . self::getFullName(true) . '</b><br>';
        $detail .= !empty($this->address) ? self::getFullAddress() . '<br>' : '';
        $detail .= !empty($this->tel) ? Yii::t('common/model', 'tel') . ' ' . $this->tel . ' ' : '';
        $detail .= !empty($this->fax) ? Yii::t('common/model', 'fax') . ' ' . $this->fax : '';
        $detail .= '<br>';
        $detail .= !empty($this->tax) ? Yii::t('common/model', 'tax') . ' ' . $this->tax.' '.self::getBranch() : '';
        return $detail;
    }

    public static function getTypeFromController() {
        $controller = Yii::$app->controller->id;
        if ($controller == 'customer') {
            $type = 'cus';
        } else if ($controller == 'supplier') {
            $type = 'sup';
        } else if ($controller == 'manufacturer') {
            $type = 'man';
        } else {
            $type = $controller;
        }
        return $type;
    }

    public function generateNewCode() {
        $type = self::getTypeFromController();
        $last = Company::find()->where('(code like :prefix) and (org like :org) and (type like :type)', [':type' => '%' . $type . '%', ':org' => '%' . Yii::$app->session['organize'] . '%', ':prefix' => Code::frontCompany() . '%'])->orderBy(['abs(substring(code, 3))' => SORT_DESC])->select('code')->asArray()->one();
        $this->code = Code::generateCompanyCode($last['code']);
    }

}
