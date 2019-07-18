<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_category".
 *
 * @property int $id
 * @property string $name
 * @property int $parent_id
 * @property double $sort_order
 * @property string $status
 */
class ProductCategory extends \common\components\CActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'status'], 'required'],
            [['parent_id'], 'integer'],
            [['sort_order'], 'number'],
            [['name'], 'string', 'max' => 255],
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
            'name' => Yii::t('common/model', 'name'),
            'parent_id' => Yii::t('common/model', 'parent'),
            'sort_order' => Yii::t('common/model', 'sort'),
            'status' => Yii::t('common/model', 'status'),
        ];
    }
    
    public static function getData($order = 'sort_order asc') {
        $data = static::find()->orderBy($order)->select('id,name')->asArray()->all();
        if (!empty($data)) {
            $arr = [];
            foreach ($data as $item) {
                $arr[$item['id']] = $item['name'];
            }
            return $arr;
        } else {
            return false;
        }
    }
    
    public static function getLabel($id) {
        $data = static::findOne($id);
        if (!empty($data)) {
            return $data['name'];
        } else {
            return false;
        }
    }
}
