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
 * @property string $color
 * @property string $caption
 * @property int $quantity
 * @property int $stock_id
 * @property double $price
 * @property double $discount
 * @property double $total
 * @property string $free
 * @property string $remark
 */
class BillItem extends CActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bill_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bill_id', 'sort_order', 'product', 'product_id', 'color', 'caption', 'quantity', 'stock_id', 'price', 'discount', 'total', 'free', 'remark'], 'required'],
            [['bill_id', 'sort_order', 'product_id', 'quantity', 'stock_id'], 'integer'],
            [['price', 'discount', 'total'], 'number'],
            [['remark'], 'string'],
            [['product'], 'string', 'max' => 100],
            [['color', 'free'], 'string', 'max' => 5],
            [['caption'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common/model', 'ID'),
            'bill_id' => Yii::t('common/model', 'Bill ID'),
            'sort_order' => Yii::t('common/model', 'Sort Order'),
            'product' => Yii::t('common/model', 'Product'),
            'product_id' => Yii::t('common/model', 'Product ID'),
            'color' => Yii::t('common/model', 'Color'),
            'caption' => Yii::t('common/model', 'Caption'),
            'quantity' => Yii::t('common/model', 'Quantity'),
            'stock_id' => Yii::t('common/model', 'Stock ID'),
            'price' => Yii::t('common/model', 'Price'),
            'discount' => Yii::t('common/model', 'Discount'),
            'total' => Yii::t('common/model', 'Total'),
            'free' => Yii::t('common/model', 'Free'),
            'remark' => Yii::t('common/model', 'Remark'),
        ];
    }
}
