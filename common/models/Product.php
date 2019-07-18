<?php

namespace common\models;

use Yii;
use common\models\ProductProperties;
use common\models\ProductStock;
use common\models\ProductPrice;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $org
 * @property string $code
 * @property string $kind
 * @property string $caption
 * @property string $detail
 * @property string $images
 * @property int $unit_id
 * @property string $alias
 * @property string $memo
 * @property string $status
 */
class Product extends \common\components\CActiveRecord {

    public $images_upload;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['org', 'code', 'kind', 'unit_id'], 'required'],
            ['code', 'unique', 'targetAttribute' => ['code']],
            ['code', 'filter', 'filter' => 'strtoupper'],
            [['detail', 'images', 'alias', 'memo'], 'string'],
            [['unit_id', 'category_id'], 'integer'],
            [['code', 'kind'], 'string', 'max' => 20],
            [['caption'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 5],
            [['images_upload'], 'file', 'maxFiles' => 10, 'skipOnEmpty' => true, 'extensions' => ['jpg', 'png']],
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
            'kind' => Yii::t('common/model', 'kind'),
            'caption' => Yii::t('common/model', 'caption'),
            'detail' => Yii::t('common/model', 'detail'),
            'images' => Yii::t('common/model', 'images'),
            'unit_id' => Yii::t('common/model', 'unit'),
            'alias' => Yii::t('common/model', 'alias'),
            'memo' => Yii::t('common/model', 'memo'),
            'category_id' => Yii::t('common/model', 'category'),
            'status' => Yii::t('common/model', 'status'),
        ];
    }

    public function beforeSave($insert) {
        $this->org = implode(',', $this->org);

        return parent::beforeSave($insert);
    }

    public function afterFind() {
        parent::afterFind();
        $this->org = explode(',', $this->org);
    }

    public function beforeDelete() {
        File::deleteFileAll($this->images);
        File::deleteDir($id);
        return parent::beforeDelete();
    }

    public function getProperties() {
        return $this->hasMany(ProductProperties::className(), ['product_id' => 'id']);
    }

    public function getPrices() {
        return $this->hasMany(ProductPrice::className(), ['product_id' => 'id']);
    }

    public function getStocks() {
        return $this->hasMany(ProductStock::className(), ['product_id' => 'id']);
    }

    public function properties($name) {
        $prop = ProductProperties::find()->where(['product_id' => $this->id, 'name' => $name])->one();
        if ($prop !== null) {
            return $prop;
        } else {
            return null;
        }
    }

    public function getPropertiesVal($name) {
        $prop = self::properties($name);
        if ($prop !== null) {
            return $prop->val;
        } else {
            return null;
        }
    }

    public function setPropertiesVal($name, $val) {
        $prop = self::properties($name);
        if ($prop !== null) {
            $prop->val = $val;
            if ($prop->save()) {
                return true;
            }
        }
        return false;
    }

    public function prefixName() {
        $arr = explode('-', $this->code);
        return strtoupper($arr[0]);
    }

    public function initProperties() {
        $prefix = self::prefixName();
        $config = Yii::$app->params['product_properties'];
        $units = Yii::$app->params['unit_properties'];
        foreach ($config as $key => $props) {
            if ($prefix == $key) {
                foreach ($props as $name) {
                    if (self::properties($name) === null) {
                        $prop = new ProductProperties();
                        $prop->product_id = $this->id;
                        $prop->name = $name;
                        $prop->val = '';
                        $prop->unit_id = $units[$name];
                        $prop->save(false);
                    }
                }
            }
        }
    }

    public function initPrice() {
        $price = ProductPrice::findOne(['product_id' => $this->id, 'item_fix' => 1]);
        if ($price === null) {
            $price = new ProductPrice();
            $price->product_id = $this->id;
            $price->quantity = 1;
            $price->price = 0;
            $price->rank = 'D';
            $price->item_fix = 1;
            $price->save(false);
        }
    }

    public function checkPrimaryProperties($name) {
        $prefix = self::prefixName();
        $config = Yii::$app->params['product_properties'];
        $arr = $config[$prefix];
        if (in_array($name, $arr)) {
            return true;
        } else {
            return false;
        }
    }

}
