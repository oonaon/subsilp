<?php

namespace common\components;

use Yii;
use yii\helpers\Html;
use common\models\MessageSource;

class CDbMessageSource extends \yii\i18n\DbMessageSource {
    
    protected function translateMessage($category, $message, $language) {
        $translate = parent::translateMessage($category, $message, $language);
        if (YII_DEBUG) {
            if (empty($translate)) {
                MessageSource::createMessage($category,$message);
                return '['.$category.']'.$message.'';
            } else {
                return $translate;
            }
        } else {
            return $translate;
        }
    }

}
