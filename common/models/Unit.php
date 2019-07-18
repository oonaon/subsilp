<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "unit".
 *
 * @property int $id
 * @property string $name
 * @property string $letter
 * @property int $parent_id
 * @property double $multiplier
 */
class Unit extends \common\components\CActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'letter', 'parent_id', 'multiplier'], 'required'],
            [['parent_id'], 'integer'],
            [['multiplier'], 'number'],
            [['sort_order'], 'double'],
            [['name'], 'string', 'max' => 100],
            [['letter'], 'string', 'max' => 20],
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
            'letter' => Yii::t('common/model', 'letter'),
            'parent_id' => Yii::t('common/model', 'parent'),
            'multiplier' => Yii::t('common/model', 'multiplier'),
            'sort_order' => Yii::t('common/model', 'sort_order'),
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
