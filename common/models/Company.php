<?php

namespace common\models;

use Yii;
use common\models\AreaDistricts;
use common\models\CompanyLocation;
use common\models\Area;
use common\components\CActiveRecord;

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
            [['district'], 'integer', 'min' => 1, 'message' => Yii::t('backend/general', 'select') . ' {attribute} '],
            ['code', 'unique', 'targetAttribute' => ['code']],
            [['branch', 'credit', 'salesman', 'district'], 'integer'],
            [['branch', 'credit', 'salesman'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 1],
            [['address', 'payment', 'memo', 'transport_note'], 'string'],
            [['postcode', 'transport', 'rank', 'status'], 'string', 'max' => 5],
            [['postcode'], 'number'],
            [['code', 'kind'], 'string', 'max' => 20],
            [['name', 'tel', 'fax', 'email', 'website'], 'string', 'max' => 100],
            [['tax'], 'string', 'max' => 13],
            [['email'], 'email'],
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
        return $model_location->save();
    }

    public function beforeSave($insert) {
        $this->org = implode(',', $this->org);
        $this->type = implode(',', $this->type);

        $district = AreaDistricts::findOne($this->district);
        if (empty($district)) {
            $this->district = 0;
            $this->amphure = 0;
            $this->province = 0;
        } else {
            $this->amphure = $district->amphure_id;
            $this->province = $district->amphure->province_id;
        }
        return parent::beforeSave($insert);
    }

    public function afterFind() {
        parent::afterFind();
        $this->org = explode(',', $this->org);
        $this->type = explode(',', $this->type);
    }

    public function getContacts() {
        return $this->hasMany(CompanyContact::className(), ['company_id' => 'id']);
    }

}
