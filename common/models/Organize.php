<?php

namespace app\models;

use Yii;
use common\components\CActiveRecord;

/**
 * This is the model class for table "organize".
 *
 * @property string $id
 * @property string $prefix
 * @property string $name
 * @property string $suffix
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
 * @property string $line
 */
class Organize extends CActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organize';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'prefix', 'name', 'suffix', 'tax', 'tel', 'fax', 'email', 'website', 'address', 'subdistrict', 'district', 'province', 'postcode', 'line'], 'required'],
            [['branch'], 'integer'],
            [['id', 'postcode'], 'string', 'max' => 5],
            [['prefix', 'suffix', 'line'], 'string', 'max' => 20],
            [['name', 'tel', 'fax', 'email', 'website', 'subdistrict', 'district', 'province'], 'string', 'max' => 100],
            [['tax'], 'string', 'max' => 13],
            [['address'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'prefix' => Yii::t('app', 'Prefix'),
            'name' => Yii::t('app', 'Name'),
            'suffix' => Yii::t('app', 'Suffix'),
            'tax' => Yii::t('app', 'Tax'),
            'branch' => Yii::t('app', 'Branch'),
            'tel' => Yii::t('app', 'Tel'),
            'fax' => Yii::t('app', 'Fax'),
            'email' => Yii::t('app', 'Email'),
            'website' => Yii::t('app', 'Website'),
            'address' => Yii::t('app', 'Address'),
            'subdistrict' => Yii::t('app', 'Subdistrict'),
            'district' => Yii::t('app', 'District'),
            'province' => Yii::t('app', 'Province'),
            'postcode' => Yii::t('app', 'Postcode'),
            'line' => Yii::t('app', 'Line'),
        ];
    }
}
