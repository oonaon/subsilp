<?php

namespace common\components;

use Yii;
use common\models\Event;
use yii\helpers\Json;

class CActiveRecord extends \yii\db\ActiveRecord {

    public $attribute_old, $attribute_new;

    public function afterFind() {   
        parent::afterFind();
        $this->attribute_old = $this->attributes;
    }
    
    public function beforeSave($insert) {
        $this->attribute_new = $this->attributes;
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        self::addEvent($this->attribute_old, $this->attribute_new);
    }

    public function addEvent($old_attribute, $new_attribute) {
        $diff = self::compare($old_attribute, $new_attribute);
        
        if (!empty($diff)) {
            $e = new Event();
            $e->controller = Yii::$app->controller->id;
            $e->action = Yii::$app->controller->action->id;
            $e->model = self::className();
            $e->model_id = $this->getPrimaryKey();
            $e->data = Json::encode($diff);
            $e->save();
        }
    }

    public static function compare($old_attribute, $new_attribute) {
        $diff = [];
        if (!empty($new_attribute)) {
            foreach ($new_attribute as $key => $new) {
                $old = $old_attribute[$key];
                if ($old != $new) {
                    $diff[$key] = [0 => $old, 1 => $new];
                }
            }
        }
        return $diff;
    }

}

?>