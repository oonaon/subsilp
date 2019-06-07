<?php

namespace common\models;

use Yii;

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
 * @property string $subdistrict
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
class Company extends \yii\db\ActiveRecord {

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
            ['code', 'unique', 'targetAttribute' => ['code']],
            [['branch', 'credit', 'salesman'], 'integer'],
            [['branch', 'credit', 'salesman'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 1],
            [['address', 'payment', 'memo', 'transport_note'], 'string'],
            [['postcode', 'transport', 'rank', 'status'], 'string', 'max' => 5],
            [['code', 'kind'], 'string', 'max' => 20],
            [['name', 'tel', 'fax', 'email', 'website', 'subdistrict', 'district', 'province'], 'string', 'max' => 100],
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
            'subdistrict' => Yii::t('common/model', 'subdistrict'),
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

    public function beforeSave($insert) {

        $this->org = implode(',', $this->org);
        $this->type = implode(',', $this->type);

        return parent::beforeSave($insert);
    }

    public function afterFind() {

        $this->org = explode(',', $this->org);
        $this->type = explode(',', $this->type);

        return parent::afterFind();
    }
    
    public function getContacts()
    {
        return $this->hasMany(CompanyContact::className(), ['company_id' => 'id']);
    }

}
