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
 * @property double $sort_order
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
            [['category', 'label'], 'required'],
       //     ['val', 'unique', 'targetAttribute' => ['category','val']],
            [['sort_order'], 'number'],
            [['status'], 'integer'],
            [['category', 'val', 'label'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('common/model', 'id'),
            'category' => Yii::t('common/model', 'category'),
            'val' => Yii::t('common/model', 'val'),
            'label' => Yii::t('common/model', 'label'),
            'sort_order' => Yii::t('common/model', 'sort'),
            'status' => Yii::t('common/model', 'status'),
        ];
    }

    public static function getData($category, $order = 'sort_order asc') {
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
