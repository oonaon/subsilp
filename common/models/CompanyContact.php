<?php

namespace common\models;

use Yii;
use common\components\CActiveRecord;
use common\models\Company;

/**
 * This is the model class for table "company_contact".
 *
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property string $contact
 * @property string $item_default
 */
class CompanyContact extends CActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_contact';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'name'], 'required'],
            [['company_id','item_default'], 'integer'],
            [['contact'], 'string'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common/model', 'id'),
            'company_id' => Yii::t('common/model', 'company'),
            'name' => Yii::t('common/model', 'name'),
            'contact' => Yii::t('common/model', 'contact'),
            'item_default' => Yii::t('common/model', 'item_default'),
        ];
    }
    
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

}
