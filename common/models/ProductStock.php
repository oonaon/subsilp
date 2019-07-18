<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_stock".
 *
 * @property int $id
 * @property int $product_id
 * @property string $color
 * @property string $name
 * @property int $quantity
 */
class ProductStock extends \common\components\CActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'color', 'name'], 'required'],
            [['product_id', 'quantity'], 'integer'],
            [['color', 'name'], 'string', 'max' => 20],
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
            'color' => Yii::t('common/model', 'color'),
            'name' => Yii::t('common/model', 'name'),
            'quantity' => Yii::t('common/model', 'quantity'),
        ];
    }
}
