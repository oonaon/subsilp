<?php

namespace common\models;

use Yii;
use common\components\CActiveRecord;

/**
 * This is the model class for table "item_alias".
 *
 * @property int $id
 * @property string $category
 * @property string $val
 * @property string $label
 * @property double $order_num
 * @property int $status
 */
class ItemAlias extends CActiveRecord {

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'item_alias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['category', 'val', 'label', 'order_num', 'status'], 'required'],
            [['order_num'], 'number'],
            [['status'], 'integer'],
            [['category', 'val', 'label'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'category' => Yii::t('app', 'Category'),
            'val' => Yii::t('app', 'Val'),
            'label' => Yii::t('app', 'Label'),
            'order_num' => Yii::t('app', 'Order Num'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public static function getData($category, $order = 'order_num asc') {
        $data = static::find()->where(['category' => $category, 'status' => self::STATUS_ACTIVE])->orderBy($order)->select('val,label')->asArray()->all();
        if (!empty($data)) {
            $arr = [];
            foreach ($data as $item) {
                $arr[$item['val']] = Yii::t('common/itemalias', $item['label']);
            }
            return $arr;
        } else {
            return false;
        }
    }

    public static function getLabel($category,$val) {
        $data = static::find()->where(['category' => $category,'val'=>$val, 'status' => self::STATUS_ACTIVE])->one();
        if (!empty($data)) {
            return Yii::t('common/itemalias', $data['label']);;
        } else {
            return false;
        }
    }
}
