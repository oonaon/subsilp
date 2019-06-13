<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "area_geographies".
 *
 * @property int $id
 * @property string $name_th
 * @property string $name_en
 */
class AreaGeographies extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'area_geographies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_th', 'name_en'], 'required'],
            [['name_th', 'name_en'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common/model', 'ID'),
            'name_th' => Yii::t('common/model', 'Name Th'),
            'name_en' => Yii::t('common/model', 'Name En'),
        ];
    }
}
