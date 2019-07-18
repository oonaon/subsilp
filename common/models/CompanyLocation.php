<?php

namespace common\models;

use Yii;
use common\models\AreaDistricts;
use common\components\CActiveRecord;

/**
 * This is the model class for table "company_location".
 *
 * @property int $id
 * @property int $company_id
 * @property int $contact_id
 * @property string $address
 * @property int $district
 * @property int $amphure
 * @property int $province
 * @property string $postcode
 * @property string $map
 * @property string $memo
 * @property int $item_default
 */
class CompanyLocation extends CActiveRecord {

    const UPLOAD_FOLDER = 'images';
    
    public $map_upload;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'company_location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['company_id', 'address', 'district', 'postcode'], 'required'],
            [['district'], 'integer', 'min' => 1, 'message' => Yii::t('backend/general', 'select') . ' {attribute} '],
            [['company_id', 'contact_id', 'item_default', 'item_fix', 'district'], 'integer'],
            [['address', 'memo', 'contact','map'], 'string'],
            [['postcode'], 'string', 'max' => 5],
            [['postcode'], 'number'],
            [['latitude','longitude'], 'double'],
            [['map_upload'], 'file', 'maxFiles' => 10,'skipOnEmpty' => true, 'extensions' => ['jpg','png','pdf','zip','ai']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('common/model', 'id'),
            'company_id' => Yii::t('common/model', 'company'),
            'contact_id' => Yii::t('common/model', 'contact_person'),
            'contact' => Yii::t('common/model', 'contact'),
            'address' => Yii::t('common/model', 'address'),
            'district' => Yii::t('common/model', 'district'),
            'amphure' => Yii::t('common/model', 'amphure'),
            'province' => Yii::t('common/model', 'province'),
            'postcode' => Yii::t('common/model', 'postcode'),
            'map' => Yii::t('common/model', 'map'),
            'latitude' => Yii::t('common/model', 'latitude'),
            'longitude' => Yii::t('common/model', 'longitude'),
            'memo' => Yii::t('common/model', 'memo'),
            'item_default' => Yii::t('common/model', 'item_default'),
            'item_fix' => Yii::t('common/model', 'item_fix'),
        ];
    }

    public function beforeSave($insert) {       
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
    
    public function beforeDelete() {
        File::deleteFileAll($this->map);
        return parent::beforeDelete();
    }   

    public function afterFind() {
        return parent::afterFind();
    }

}
