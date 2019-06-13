<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "area_amphures".
 *
 * @property int $id
 * @property string $code
 * @property string $name_th
 * @property string $name_en
 * @property int $province_id
 */
class AreaAmphures extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'area_amphures';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name_th', 'name_en'], 'required'],
            [['province_id'], 'integer'],
            [['code'], 'string', 'max' => 4],
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
            'province_id' => Yii::t('common/model', 'Province ID'),
        ];
    }
    
    public function getProvince(){
        return $this->hasOne(AreaProvinces::className(), ['id' => 'province_id']);
    }
}
