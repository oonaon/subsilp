<?php

namespace common\models;

use Yii;
use common\models\Unit;

/**
 * This is the model class for table "product_properties".
 *
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property string $val
 * @property int $unit_id
 */
class ProductProperties extends \common\components\CActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'product_properties';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['product_id', 'name', 'unit_id'], 'required'],
            ['name', 'unique', 'targetAttribute' => ['product_id', 'name']],
            [['product_id', 'unit_id'], 'integer'],
            [['name', 'val'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('common/model', 'id'),
            'product_id' => Yii::t('common/model', 'product'),
            'name' => Yii::t('common/model', 'name'),
            'val' => Yii::t('common/model', 'val'),
            'unit_id' => Yii::t('common/model', 'unit'),
        ];
    }

    public function getUnit() {
        return $this->hasOne(Unit::className(), ['id' => 'product_id']);
    }

    public function getProduct() {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
