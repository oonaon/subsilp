<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "area_districts".
 *
 * @property string $id
 * @property int $postcode
 * @property string $name_th
 * @property string $name_en
 * @property int $amphure_id
 */
class AreaDistricts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'area_districts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'postcode', 'name_th', 'name_en'], 'required'],
            [['postcode', 'amphure_id'], 'integer'],
            [['id'], 'string', 'max' => 6],
            [['name_th', 'name_en'], 'string', 'max' => 150],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common/model', 'ID'),
            'postcode' => Yii::t('common/model', 'Postcode'),
            'name_th' => Yii::t('common/model', 'Name Th'),
            'name_en' => Yii::t('common/model', 'Name En'),
            'amphure_id' => Yii::t('common/model', 'Amphure ID'),
        ];
    }
    
    public function getAmphure(){
        return $this->hasOne(AreaAmphures::className(), ['id' => 'amphure_id']);
    }
    
}
