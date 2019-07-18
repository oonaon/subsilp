<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_price".
 *
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property string $rank
 * @property double $price
 */
class ProductPrice extends \common\components\CActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_price';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['product_id', 'quantity'], 'integer'],
            [['price'], 'number'],
            [['rank'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common/model', 'id'),
            'product_id' => Yii::t('common/model', 'product'),
            'quantity' => Yii::t('common/model', 'quantity'),
            'rank' => Yii::t('common/model', 'rank'),
            'price' => Yii::t('common/model', 'price'),
        ];
    }
}
