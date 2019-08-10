<?php

namespace common\models;

use Yii;
use common\components\CActiveRecord;

/**
 * This is the model class for table "bill_item".
 *
 * @property int $id
 * @property int $bill_id
 * @property int $sort_order
 * @property string $product
 * @property int $product_id
 * @property string $sub
 * @property string $caption
 * @property int $quantity
 * @property int $stock_id
 * @property double $price
 * @property double $discount
 * @property double $total
 * @property string $free
 * @property string $remark
 */
class BillItem extends CActiveRecord {

    public $old, $price_suggest;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'bill_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['product_id', 'stock_id'], 'required'],
            [['bill_id', 'sort_order', 'product_id', 'quantity', 'stock_id', 'old'], 'integer'],
            [['price', 'discount', 'total', 'quantity'], 'number'],
            [['remark'], 'string'],
            [['sub', 'free'], 'string', 'max' => 5],
            [['caption'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('common/model', 'id'),
            'bill_id' => Yii::t('common/model', 'bill'),
            'sort_order' => Yii::t('common/model', 'sort_order'),
            'product_id' => Yii::t('common/model', 'product'),
            'sub' => Yii::t('common/model', 'color'),
            'caption' => Yii::t('common/model', 'caption'),
            'quantity' => Yii::t('common/model', 'quantity'),
            'stock_id' => Yii::t('common/model', 'stock'),
            'price' => Yii::t('common/model', 'price'),
            'discount' => Yii::t('common/model', 'discount'),
            'total' => Yii::t('common/model', 'total'),
            'free' => Yii::t('common/model', 'free'),
            'remark' => Yii::t('common/model', 'remark'),
        ];
    }

    public function afterFind() {
        parent::afterFind();
        $this->old = $this->product_id;
    }

}
