<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "company_contact".
 *
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property string $contact
 * @property string $memo
 */
class CompanyContact extends \yii\db\ActiveRecord
{
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
            [['company_id'], 'integer'],
            [['contact', 'memo'], 'string'],
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
            'memo' => Yii::t('common/model', 'memo'),
        ];
    }
}
