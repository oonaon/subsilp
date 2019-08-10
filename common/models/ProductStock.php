<?php

namespace common\models;

use Yii;

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
            [['product_id', 'sub', 'name'], 'required'],
            ['name', 'unique', 'targetAttribute' => ['product_id','sub','name']],
            [['product_id', 'quantity'], 'integer'],
            [['sub', 'name'], 'string', 'max' => 20],
            [['caption'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 5],
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
            'sub' => Yii::t('common/model', 'color'),
            'name' => Yii::t('common/model', 'name'),
            'quantity' => Yii::t('common/model', 'quantity'),
            'caption' => Yii::t('common/model', 'caption'),
            'status' => Yii::t('common/model', 'status'),
        ];
    }
}
