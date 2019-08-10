<?php

namespace common\components;

use Yii;

class HeadNavigator {

    public function header() {
        return Yii::t('backend/menu', Yii::$app->controller->id);
    }

    public function breadcrumbs($label = '', $url = '') {
        $controller = Yii::$app->controller->id;
        $action = Yii::$app->controller->action->id;
        $id = empty($_GET['id']) ? '' : $_GET['id'];

        $arr = [];
        $arr[] = ['label' => Yii::t('backend/menu', $controller), 'url' => ['/' . $controller]];

        if ($action == 'update' && empty($id)) {
            $arr[] = ['label' => Yii::t('backend/button', 'add')];
        } else {
            if (!empty($label)) {
                if (empty($url)) {
                    $arr[] = ['label' => $label];
                } else {
                    $arr[] = ['label' => $label, 'url' => $url];
                }
            }

            $text = explode('-', $action);

            if (in_array($text[0], ['index'])) {
                
            } else if ($text[0] == 'update') {
                if (!empty($id)) {
                    $arr[] = ['label' => Yii::t('backend/tab', 'view'), 'url' => ['view', 'id' => $_GET['id']]];
                } else {
                    $arr[] = ['label' => Yii::t('backend/tab', 'view'), 'url' => ['view']];
                }
            } else {
                $arr[] = ['label' => Yii::t('backend/tab', $text[0]), 'url' => [$text[0], 'id' => $_GET['id']]];
            }

            if (!empty($text[1]) && $text[1] == 'update') {
                $arr[] = ['label' => Yii::t('backend/button', 'manage')];
            } else if ($action == 'update') {
                $arr[] = ['label' => Yii::t('backend/button', 'update')];
            }
        }

        return $arr;
    }

}
