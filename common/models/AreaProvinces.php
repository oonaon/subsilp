<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "area_provinces".
 *
 * @property int $id
 * @property string $code
 * @property string $name_th
 * @property string $name_en
 * @property int $geography_id
 */
class AreaProvinces extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'area_provinces';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name_th', 'name_en'], 'required'],
            [['geography_id'], 'integer'],
            [['code'], 'string', 'max' => 2],
            [['name_th', 'name_en'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common/model', 'ID'),
            'code' => Yii::t('common/model', 'Code'),
            'name_th' => Yii::t('common/model', 'Name Th'),
            'name_en' => Yii::t('common/model', 'Name En'),
            'geography_id' => Yii::t('common/model', 'Geography ID'),
        ];
    }
    
    public function getGeography(){
        return $this->hasOne(AreaGeographies::className(), ['id' => 'geography_id']);
    }
}
