<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property int $user_id
 * @property string $create_at
 * @property string $controller
 * @property string $action
 * @property string $model
 * @property int $model_id
 * @property string $data
 */
class Event extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'event';
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'model_id'], 'integer'],
            [['create_at'], 'safe'],
            [['data'], 'string'],
            [['controller', 'action', 'model'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'id',
            'user_id' => 'user',
            'create_at' => 'create_at',
            'controller' => 'controller',
            'action' => 'action',
            'model' => 'model',
            'model_id' => 'model',
            'data' => 'data',
        ];
    }

}
